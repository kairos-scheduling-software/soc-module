<?php

class HomeController extends BaseController {

	public function home()
	{
		return View::make('home')->with([
			'page_name'	=>	'HOME'
		]);
	}

}
