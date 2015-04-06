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

		$year = date("Y");
		$years = [];
		
		for($start = 2000; $start < $year + 5; $start++)
		{
			$years[] = $start;
		}

		return View::make('import-schedule')->with([
				'page_name'	=>	'Import Schedule',
				'selected'	=> 	$selected,
				'schedules' =>	$user->schedules,
				'years'		=> 	$years,
				'currentYear'=> $year,
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
		$semester = Input::get('semester');
		$year = Input::get('year');

		//eventually add mimes:csv when I get a chance to find the correct php configuration
		$rules = array(
			'uploadfile' => 'required',
			'scheduleName' => 'required',
			'year'		   => 'required|numeric',
			'semester'	   => 'required'
		);
  		$validator = Validator::make(array('uploadfile'=> $importFile, 'scheduleName' => $scheduleName, 'year' => $year, 'semester' => $semester), $rules);

  		if($validator->passes())
  		{
			

			$schedule = Schedule::create(
			array(
				'name' => $scheduleName,
				'last_edited_by' => $user->id,
				'semester'	=>	$semester,
				'year'		=> $year,
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
				$schedule->delete();
				$message = "Invalid headers in the import file";
			}

			return Redirect::route('import-schedule')->with('global', $message);
  		}
  		else
  		{
  			echo $year;
  			return $semester;
  			return Redirect::route('import-schedule')->withErrors($validator) -> withInput()->with([
				'selected' 	=>	'Import Full'
			]);
  		}

		
	}

	public function import_resources($mode)
	{
		ini_set('auto_detect_line_endings', true);

		$scheduleName = Input::get('schedules');
		$user = Auth::user();
		$found = false;
		$scheduleToModify;

		$selected = 'Import Constraint';

		if($mode == 'room')
		{
			$selected = 'Import Rooms';
		}
		else if($mode == 'professor')
		{
			$selected = 'Import Professors';
		}

		// Make sure the schedule exisits
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
		if($mode == 'constraint')
			$removePrevConstraint = Input::get('Replace');
		else
			$removePrevConstraint = false;

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
  				if($removePrevConstraint)
  				{
  					$scheduleToModify->removeConstraints();
  				}

				$returnData = '';
				
				if($mode == 'constraint')
				{
					$returnData = DataConvertUtils::importConstraint($scheduleToModify, $importFile);
				}
				else if($mode == 'room')
				{
					$returnData = DataConvertUtils::importRooms($scheduleToModify, $importFile);
				}
				else if($mode == 'professor')
				{
					$returnData = DataConvertUtils::importProfessors($scheduleToModify, $importFile);
				}
				

				$message = ($returnData['percentPassed'] * 100). "% of the " . $mode ."s were added sucessfully";
				
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
				return $e->getMessage();
			}

			return Redirect::route('import-schedule')
			->with([
					'selected' 	=>	$selected,
					'global' 	=> 	$message
				]);
  		}
  		else
  		{
  			return Redirect::route('import-schedule')->withErrors($validator) -> withInput()
  			->with([
					'selected' 	=>	$selected
				]);
  		}		
	}
}

