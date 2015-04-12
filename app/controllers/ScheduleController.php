<?php

class ScheduleController extends BaseController {

	public function dashboard()
	{
		// Fetch all of the current user's schedules
		$schedules = Auth::user()->schedules;

		return View::make('dashboard')->with([
			'page_name'	=>	'DASHBOARD',
			'schedules'	=>	$schedules
		]);
	}

	public function load_sched_admin($id) 
	{
		$sched = Schedule::find($id);

		if (!$sched)
			return Response::json(['error' => 'Could not load schedule'], 500);
		else
			return View::make('blocks.dashboard-right')->withSched($sched);
	}

	public function view_schedule()
	{
		$id = Input::get('id');
		$schedule = Schedule::find($id);
/*
		if(!$id || !$schedule)
			return Redirect::route('dashboard');
		else
		{*/
			$schedule = Schedule::find($id);
			return View::make('sched_01')->with([
				'page_name'	=>	'Schedule View',
				'schedule'	=>	$schedule
			]);
		//}
	}

	public function edit_schedule($id)
	{
		$schedule = Schedule::find($id);
		$time_blocks = Etime::where('standard_block', '=', 1)->where('starttm', '!=', '0730')->get();

		if (!$schedule)
			return Redirect::route('dashboard'); // TODO: redirect to 404

		return View::make('editor.sched-editor')->with([
			'page_name'		=>	'Schedule Editor',
			'schedule'		=>	$schedule,
			'time_blocks'	=>	$time_blocks
		]);
	}

	public function delete_schedule($id)
	{
		$schedule = Schedule::find($id);

		if($schedule->delete())
			return Response::json(['success' => 'Schedule deleted!'], 200);
		else
			return Response::json(['error' => 'Could not delete schedule at this time'], 500);
	}

	public function create_schedule()
	{
		$name = Input::get('sched_name');
		$semester = Input::get('semester');
		$year = Input::get('year');
		$user = Auth::user();

		// Make sure the schedule name is unique
		foreach($user->schedules as $schedule)
		{
			if ($schedule->name == $name)
				return Response::json(['error' => 'Name already in use'], 500);
		}
		$verifySemester = (strcasecmp($semester, 'Fall') == 0) || (strcasecmp($semester, 'Spring') !== 0) || (strcasecmp($semester, 'Summer') !== 0);
		
		if(!is_numeric($year) || (strcasecmp($semester, 'Fall') !== 0) || !$verifySemester)
		{
			return Response::json(['error' => 'Could not create schedule at this time'], 500);
		}

		$schedule = new Schedule();
		$schedule->name = $name;
		$schedule->last_edited_by = $user->id;
		$schedule->semester = $semester;
		$schedule->year = $year;
		$schedule->description = "";

		if ($schedule->save())
		{
			$user->schedules()->attach($schedule->id);
			return URL::route('edit-sched', $schedule->id);
		}
		else
			return Response::json(['error' => 'Could not create schedule at this time'], 500);
	}

	public function data_entry1($sched_id)
	{
		$schedule = Schedule::find($sched_id);

		if ($schedule)
			return View::make('data-entry1')->with([
				'page_name'	=>	'Data Entry',
				'schedule'	=>	$schedule
			]);
		else
			return "<h1>ERROR</h1>"; // TODO: send back a 404 page
	}

	public function add_class($sched_id)
	{
		$schedule = Schedule::find($sched_id);

		if($schedule)
		{
			$class = new models\Event();
			$class->name = Input::get('name');
			$class->professor = Professor::all()->first()->id; // TODO: fix professor foreign key
			$class->room_id = Room::all()->first()->id;
			$class->schedule_id = $schedule->id;
			
			if($class->save())
			{
				return View::make('blocks.class-listing')->with([
					'class'	=>	$class,
					'schedule'	=>	$schedule
				]);
			}			
		}
		else
			return Response::json(['error' => 'Could not add class at this time.'], 500);
	}

	public function edit_class($sched_id, $class_id)
	{
		$schedule = Schedule::find($sched_id);
		$class = models\Event::find($class_id);
		$class->name = Input::get('name');

		if($class->save())
		{
			return View::make('blocks.class-listing')->with([
				'schedule'	=>	$schedule,
				'class'		=>	$class
			]);
		}
		else
			return Response::json(['error' => 'Could not edit class at this time.'], 500);
	}

	public function delete_class($sched_id, $class_id)
	{
		$class = models\Event::find($class_id);

		if($class)
		{
			$class->delete();
			return Response::json(['success' => 'Successfully deleted'], 200);	
		}
		else
			return Response::json(['error' => 'Could not remove class at this time.'], 500);		
	}

	public function data_entry2($sched_id, $class_id)
	{
		$schedule = Schedule::find($sched_id);

		if ($schedule)
			return View::make('data-entry2')->with([
				'page_name'	=>	'Data Entry',
				'schedule'	=>	$schedule
			]);
		else
			return "<h1>ERROR</h1>"; // TODO: send back a 404 page
	}

	public function data_entry3($sched_id)
	{
		$schedule = Schedule::find($sched_id);
		$hard_const_keys = [
			"Minimum Capacity", "Professor", "Block Length", "Avoid Conflict With", "Avoid Conflict With"
		];
		
		$hard_const_vals = [
			"110", "Joe Zachary", "80 min", "CS 3810", "CS 2100"
		];

		$soft_const_keys = [
			"Days", "Avoid Conflict With", "Avoid Conflict With", "Time of Day"
		];
		
		$soft_const_vals = [
			"Mon, Wed", "CS 3130", "MATH 2100", "Morning"
		];

		$classes = [
			1400, 1410, 2420, 3500, 3505, 3810, 4150, 4400, 4540, 4000, 
			4500, 4480, 4962, 4964, 5650, 6320, 3200, 3100, 3130, 5140, 
			5610, 6370, 7931, 5460, 5789, 3700, 6963, 3992, 6780, 5470
		];

		if ($schedule)
			return View::make('data-entry3')->with([
				'page_name'			=>	'Data Entry',
				'classes'			=>	$classes,
				'hard_const_keys'	=>	$hard_const_keys,
				'hard_const_vals'	=>	$hard_const_vals,
				'soft_const_keys'	=>	$soft_const_keys,
				'soft_const_vals'	=>	$soft_const_vals,
				'schedule'	=>	$schedule
			]);
		else
			return "<h1>ERROR</h1>"; // TODO: send back a 404 page
	}

	public function data_entry4($sched_id)
	{
		$schedule = Schedule::find($sched_id);
		$hard_const_keys = [
			"Minimum Capacity", "Professor", "Block Length", "Avoid Conflict With", "Avoid Conflict With"
		];
		
		$hard_const_vals = [
			"110", "Joe Zachary", "80 min", "CS 3810", "CS 2100"
		];

		$soft_const_keys = [
			"Days", "Avoid Conflict With", "Avoid Conflict With", "Time of Day"
		];
		
		$soft_const_vals = [
			"Mon, Wed", "CS 3130", "MATH 2100", "Morning"
		];

		$classes = [
			1400, 1410, 2420, 3500, 3505, 3810, 4150, 4400, 4540, 4000, 
			4500, 4480, 4962, 4964, 5650, 6320, 3200, 3100, 3130, 5140, 
			5610, 6370, 7931, 5460, 5789, 3700, 6963, 3992, 6780, 5470
		];

		if ($schedule)
			return View::make('data-entry4')->with([
				'page_name'			=>	'Data Entry',
				'classes'			=>	$classes,
				'hard_const_keys'	=>	$hard_const_keys,
				'hard_const_vals'	=>	$hard_const_vals,
				'soft_const_keys'	=>	$soft_const_keys,
				'soft_const_vals'	=>	$soft_const_vals,
				'schedule'	=>	$schedule
			]);
		else
			return "<h1>ERROR</h1>"; // TODO: send back a 404 page		
	}

	public function e_add_class()
	{
		// TODO: add error checking!!
		$schedule = Schedule::find(Input::get('sched_id'));

		// Add & save the room
		$room = Room::firstOrCreate(['name' => Input::get('room_name'), 'schedule_id' => $schedule->id]);
		$room->capacity = 80;
		$room->save();/*
		if (!$room)
		{
			$room = new Room();
			$room->name = Input::get('room_name');
			$room->capacity = 80;
			$room->schedule_id = $schedule->id;
			$room->save();
		}*/

		// Add & save the class
		$class = new models\Event();
		$class->name = Input::get('class_name');
		$class->professor = 1;
		$class->schedule_id = $schedule->id;
		$class->room_id = $room->id;
		$class->class_type = "Lecture";
		$class->etime_id = Input::get('block_id');
		$class->save();

		// Call check on comm library
		try {
			$result = Communication::sendCheck($schedule->id);

			return $result;
		}
		catch (Exception $e)
		{
				return Response::json(['error' => 'Could not check schedule'], 500);
		}
	}

	public function e_remove_class()
	{
		$id = Input::get('id');
		$schedule = Schedule::find(Input::get('schedule'));

		$class = models\Event::find($id);
		$class->delete();

		try {
			$result = Communication::sendCheck($schedule->id);

			return $result;
		}
		catch (Exception $e)
		{
				return Response::json(['error' => 'Could not check schedule'], 500);
		}
	}

	public function update_description($sched_id)
	{
		$schedule = Schedule::find($sched_id);

		if(!$schedule)
		{
			return Resonse::json(['error' => 'could not find the schedule to update'], 500);
		}

		$schedule->description = Input::get('data');
		if($schedule->save())
		{
			return Response::json(['success' => 'Successfully updated the description'], 200);
		}

		return Resonse::json(['error' => 'could not find the schedule to update'], 500);
	}

	public function branch_schedule($idToCopy)
	{

		$scheduleToCopy = Schedule::find($idToCopy);

		if(!$scheduleToCopy)
		{
			return Resonse::json(['error' => 'could not find the schedule to copy'], 500);
		}

		$name = Input::get('sched_name');
		$semester = Input::get('semester');
		$year = Input::get('year');
		$user = Auth::user();

		// Make sure the schedule name is unique
		foreach($user->schedules as $schedule)
		{
			if ($schedule->name == $name)
				return Response::json(['error' => 'Name already in use'], 500);
		}

		$verifySemester = (strcasecmp($semester, 'Fall') == 0) || (strcasecmp($semester, 'Spring') !== 0) || (strcasecmp($semester, 'Summer') !== 0);
		
		if(!is_numeric($year) || (strcasecmp($semester, 'Fall') !== 0) || !$verifySemester)
		{
			return Response::json(['error' => 'Could not create schedule at this time'], 500);
		}

		$schedule = new Schedule();
		$schedule->name = $name;
		$schedule->last_edited_by = $user->id;
		$schedule->semester = $semester;
		$schedule->year = $year;
		$schedule->description = $scheduleToCopy->description;

		if ($schedule->save())
		{
			try
			{
				//TODO additional Error Checking
				$user->schedules()->attach($schedule->id);

				foreach ($scheduleToCopy->events as $event) 
				{
					$room = Room::find($event->room_id);
					$professor = Professor::find($event->professor);
					$schedule->rooms()->sync([$room->id], false);
					$schedule->rooms()->sync([$professor->id], false);

					$eventToCopy = models\Event::firstOrCreate(
                 	array(
                    	'name' => $event->name,
                    	'professor' => $event->professor,
                    	'schedule_id' => $schedule->id,
                    	'room_id' => $room->id,
                    	'class_type' => $event->class_type,
                    	'title' => $event->title,
                    	'etime_id' => $event->etime_id
            		));

            		foreach ($event->constraints as $constraint) 
            		{
            			$eventToFindFromScheduleToCopy = models\Event::find($constraint->value);

            			$constraintEventToCopy = models\Event::firstOrCreate(
                 		array(
                    		'name' => $eventToFindFromScheduleToCopy->name,
                    		'professor' => $eventToFindFromScheduleToCopy->professor,
                    		'schedule_id' => $schedule->id,
                    		'room_id' => $room->id,
                    		'class_type' => $eventToFindFromScheduleToCopy->class_type,
                    		'title' => $eventToFindFromScheduleToCopy->title,
                    		'etime_id' => $eventToFindFromScheduleToCopy->etime_id
            			));

            			$constraint = Constraint::make(
            			array(
            				'key' => $constraint->key,
            				'value' => $constraintEventToCopy->id,
            				'event_id' => $eventToCopy->id
            			));
            		}
				}
			}
			catch(Exception $e)
			{
				$schedule->delete();
				return Response::json(['error' => 'Could not create schedule at this time'], 500);
			}

			return URL::route('dashboard');
		}
		else
			return Response::json(['error' => 'Could not create schedule at this time'], 500);

	}

}
