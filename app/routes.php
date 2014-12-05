<?php

Route::get('/', array(
		'as' => 'home',
		'uses' => 'HomeController@home'
));

Route::get('/createSchedule/{scheduleId}', array(
			'as'=> 'createSchedule',
			'uses' => 'APIController@create'
	));

Route::group(array('before'=> 'guest'), function()
{

	//used for email account activation
	Route::get('/activate/{code}', array(
			'as' => 'activate',
			'uses' => 'AccountController@activate'
	));

	Route::group(array('before'=> 'guest'), function()
	{
		//used for email account activation
		Route::get('/activate/{code}', array(
				'as' => 'activate',
				'uses' => 'AccountController@activate'
		));

		//used for registering accounts
		Route::get('/register', array(
			'as' => 'register',
			'uses' => 'AccountController@register'
		));

		Route::post('/register', array(
				'as'=> 'register-post',
				'uses' => 'AccountController@postRegister'
		));

		//used for signing in
		Route::get('/login', array(
			 'as' => 'getLogin',
			 'uses' => 'AccountController@getLogin'
		));

		Route::post('/login', array(
			 'as' => 'postLogin',
			 'uses' => 'AccountController@postLogin'
		));
	});

	Route::group(array('before'=> 'auth'), function()
	{
		Route::get('/logout', array(
			'as' => 'logout',
			'uses' => 'AccountController@logout'
		));

		Route::get('/dashboard', array(
			'as'	=>	'dashboard',
			'uses'	=>	'ScheduleController@dashboard'
		));
	});

?>
