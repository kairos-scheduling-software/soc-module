<?php

class HomeController extends BaseController {

	public function home()
	{
		if (Auth::check())
			return Redirect::route('dashboard');
		else
			return View::make('landing_page')->with([
				'page_name'	=>	'LANDING'
			]);
	}

}
