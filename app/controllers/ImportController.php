<?php

class ImportController extends BaseController 
{
	public function import_schedule()
	{
		$user = Auth::user();
		$selected = 'Import Full';
		$global = "";

		if(Session::get('selected') !== null)
		{
			$selected = Session::get('selected');
		}

		if(Session::get('global') !== null)
		{
			$global = Session::get('global');
		}


		return View::make('import-schedule')->with([
				'page_name'	=>	'Import Schedule',
				'selected'	=> 	$selected,
				'schedules' =>	$user->schedules,
				'global'	=>	$global
			]);
	}

	public function import_post()
	{
		ini_set('auto_detect_line_endings', true);

		$scheduleName = Input::get('ScheduleName-text');
		$user = Auth::user();
		$inUse = false;

		// Make sure the schedule name is unique
		foreach($user->schedules as $schedule)
		{
			if ($schedule->name == $scheduleName)
				return Redirect::route('import-schedule')->withErrors(array('scheduleName' => "The schedule name is already in use")) -> withInput()
				->with([
					'selected' 	=>	'Import Full'
				]);
			
		}

		$importFile = Input::file('import');

		//eventually add mimes:csv when I get a chance to find the correct php configuration
		$rules = array(
			'uploadfile' => 'required',
			'scheduleName' => 'required'
		);
  		$validator = Validator::make(array('uploadfile'=> $importFile, 'scheduleName' => $scheduleName), $rules);

  		if($validator->passes())
  		{
			

			$schedule = Schedule::create(
			array(
				'name' => $scheduleName,
				'last_edited_by' => $user->id,
				'description' => ""
			));

			$user->schedules()->attach($schedule->id);
			
			$message = "";

			try
			{
				$returnData = DataConvertUtils::importFullSchedule($schedule, $importFile);
				$message = ($returnData['percentPassed'] * 100) . "% of the schedule was uploaded sucessfully.";
				
				if($returnData['percentPassed'] < .90)
				{
					$schedule->delete();
					$message = "There were too many failed rows to create your schedule. Only " . ($returnData['percentPassed'] * 100) . "% passed.";
				}

				if($returnData['percentPassed'] !== 1)
				{
					$message = $message . "</br>The failed rows are: ";
				

					for ($i = 0; $i < count($returnData['rowsFailed']); $i++)
					{
						$message = $message . $returnData['rowsFailed'][$i];

						if($i < (count($returnData['rowsFailed']) - 1))
						{
							$message = $message . ", ";
						}
					}
				}

				
			}
			catch(Exception $e)
			{
				echo $e->getMessage();
				$schedule->delete();
				$message = "Invalid headers in the import file";
			}

			return Redirect::route('import-schedule')->with('global', $message);
  		}
  		else
  		{
  			return Redirect::route('import-schedule')->withErrors($validator) -> withInput()->with([
				'selected' 	=>	'Import Full'
			]);
  		}

		
	}

	public function import_constraint()
	{
		ini_set('auto_detect_line_endings', true);

		$scheduleName = Input::get('schedules');
		$user = Auth::user();
		$found = false;
		$scheduleToModify;

		// Make sure the schedule name is unique
		foreach($user->schedules as $schedule)
		{
			if ($schedule->name == $scheduleName)
			{
				$scheduleToModify = $schedule;
				$found = true;
			}
		}

		if(!$found)
		{
			return Redirect::route('import-schedule')->withErrors(array('select schedule' => "The schedule name provided was not found")) -> withInput()
			->with([
					'selected' 	=>	'Import Constraint'
				]);
		}

		$importFile = Input::file('import');

		//eventually add mimes:csv when I get a chance to find the correct php configuration
		$rules = array(
			'uploadfile' => 'required',
			'select schedule' => 'required'
		);
  		$validator = Validator::make(array('uploadfile'=> $importFile, 'select schedule' => $scheduleName), $rules);

  		if($validator->passes())
  		{	
  			$message = "";

  			try
  			{
				$returnData= DataConvertUtils::importConstraint($scheduleToModify, $importFile);

				$message = ($returnData['percentPassed'] * 100). "% of the constraints were added sucessfully";
				
				if($returnData['percentPassed'] !== 1)
				{
					$message = $message . "</br>The failed rows are: ";
				

					for ($i = 0; $i < count($returnData['rowsFailed']); $i++)
					{
						$message = $message . $returnData['rowsFailed'][$i];

						if($i < (count($returnData['rowsFailed']) - 1))
						{
							$message = $message . ", ";
						}
					}
				}
			}
			catch(Exception $e)
			{
				$message = "Invalid headers in the import file";
			}

			return Redirect::route('import-schedule')
			->with([
					'selected' 	=>	'Import Constraint',
					'global' 	=> 	$message
				]);
  		}
  		else
  		{
  			return Redirect::route('import-schedule')->withErrors($validator) -> withInput()
  			->with([
					'selected' 	=>	'Import Constraint'
				]);
  		}		
	}
}