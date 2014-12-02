<?php

class HomeController extends BaseController {

	public function home()
	{
		return View::make('landing_page')->with([
			'page_name'	=>	'LANDING'
		]);
	}

}
