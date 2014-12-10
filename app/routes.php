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

	Route::post('/load-sched-admin/{id}', array(
		'as'	=>	'sched-admin',
		'uses'	=>	'ScheduleController@load_sched_admin'
	));

	Route::get('/view-schedule', array(
		'as'	=>	'view-sched',
		'uses'	=>	'ScheduleController@view_schedule'
	));

	Route::post('/delete-schedule/{id}', array(
		'as'	=>	'delete-sched',
		'uses'	=>	'ScheduleController@delete_schedule'
	));

	Route::post('/create-schedule', array(
		'as'	=>	'create-sched',
		'uses'	=>	'ScheduleController@create_schedule'
	));

	Route::get('/data-entry1/{sched_id}', array(
		'as'	=>	'data-entry1',
		'uses'	=>	'ScheduleController@data_entry1'
	));

	Route::post('/schedule/{id}/add-class', array(
		'as'	=>	'add-class',
		'uses'	=>	'ScheduleController@add_class'
	));

	Route::get('/data-entry2/{sched_id}', array(
		'as'	=>	'data-entry2',
		'uses'	=>	'ScheduleController@data_entry2'
	));

	Route::get('/data-entry3/{sched_id}', array(
		'as'	=>	'data-entry3',
		'uses'	=>	'ScheduleController@data_entry3'
	));
});