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

		if(!$id)
			return Redirect::route('dashboard');
		else
		{
			$schedule = Schedule::find($id);
			return View::make('sched_01')->with([
				'page_name'	=>	'Schedule View'
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
		$schedule->json_schedule = "NA";
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
			$class->professor = 1; // TODO: fix professor foreign key
			$class->schedule_id = $schedule->id;
			
			if($class->save())
			{
				return View::make('blocks.class-listing')->withClass($class);	
			}			
		}
		else
			return "<h1>ERROR</h1>"; // TODO: send back a 404 page
	}

	public function data_entry2($sched_id)
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

		if ($schedule)
			return View::make('data-entry3')->with([
				'page_name'	=>	'Data Entry',
				'schedule'	=>	$schedule
			]);
		else
			return "<h1>ERROR</h1>"; // TODO: send back a 404 page
	}

}
