<?php

class ScheduleController extends BaseController {

	public function dashboard()
	{
		// TODO: Fetch all of the current user's schedules

		$schedules = Auth::user()->schedules;

		return View::make('dashboard')->with([
			'page_name'	=>	'DASHBOARD',
			'schedules'	=>	$schedules
		]);
	}

}
