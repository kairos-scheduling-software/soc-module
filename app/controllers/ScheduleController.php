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
		
	}

}