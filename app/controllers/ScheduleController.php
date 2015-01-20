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

		if(!$id || !$schedule)
			return Redirect::route('dashboard');
		else
		{
			$schedule = Schedule::find($id);
			return View::make('sched_01')->with([
				'page_name'	=>	'Schedule View',
				'schedule'	=>	$schedule
			]);
		}
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
		$user = Auth::user();

		// Make sure the schedule name is unique
		foreach($user->schedules as $schedule)
		{
			if ($schedule->name == $name)
				return Response::json(['error' => 'Name already in use'], 500);
		}

		$schedule = new Schedule();
		$schedule->name = $name;
		$schedule->last_edited_by = $user->id;
		$schedule->description = "Just another test schedule";

		if ($schedule->save())
		{
			$user->schedules()->attach($schedule->id);
			return URL::route('data-entry1', $schedule->id);
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

}
