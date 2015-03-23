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
        'as'    =>  'dashboard',
        'uses'  =>  'ScheduleController@dashboard'
    ));

    Route::post('/load-sched-admin/{id}', array(
        'as'    =>  'sched-admin',
        'uses'  =>  'ScheduleController@load_sched_admin'
    ));

    Route::get('/view-schedule', array(
        'as'    =>  'view-sched',
        'uses'  =>  'ScheduleController@view_schedule'
    ));

    Route::get('/edit-schedule/{id}', array(
        'as'    =>  'edit-sched',
        'uses'  =>  'ScheduleController@edit_schedule'
    ));

    Route::post('/delete-schedule/{id}', array(
        'as'    =>  'delete-sched',
        'uses'  =>  'ScheduleController@delete_schedule'
    ));

    Route::post('/create-schedule', array(
        'as'    =>  'create-sched',
        'uses'  =>  'ScheduleController@create_schedule'
    ));

    //import schedule routes
    Route::get('/import-schedule', array(
        'as' => 'import-schedule',
        'uses' => 'ImportController@import_schedule'
    ));

    Route::post('/import-schedule', array(
        'as' => 'import-post',
        'uses' => 'ImportController@import_post'
    ));

    Route::post('/import-constraint', array(
        'as' => 'import-constraint',
        'uses' => 'ImportController@import_constraint'
    ));

    Route::post('/branch-schedule/{sched_id}', array(
        'as' => 'branch-sched',
        'uses' => 'ScheduleController@branch_schedule'
    ));

    Route::get('/data-entry1/{sched_id}', array(
        'as'    =>  'data-entry1',
        'uses'  =>  'ScheduleController@data_entry1'
    ));

    Route::post('/schedule/{id}/add-class', array(
        'as'    =>  'add-class',
        'uses'  =>  'ScheduleController@add_class'
    ));

    Route::post('/schedule/{id}/edit-class/{class_id}', array(
        'as'    =>  'edit-class',
        'uses'  =>  'ScheduleController@edit_class'
    ));

    Route::post('/schedule/{id}/delete-class/{class_id}', array(
        'as'    =>  'delete-class',
        'uses'  =>  'ScheduleController@delete_class'
    ));

    Route::get('/data-entry2/{sched_id}', array(
        'as'    =>  'data-entry2',
        'uses'  =>  'ScheduleController@data_entry2'
    ));

    Route::get('/data-entry3/{sched_id}', array(
        'as'    =>  'data-entry3',
        'uses'  =>  'ScheduleController@data_entry3'
    ));

    Route::get('/data-entry4/{sched_id}', array(
        'as'    =>  'data-entry4',
        'uses'  =>  'ScheduleController@data_entry4'
    ));

    Route::get('/account/manage', array(
        'as'    =>  'manage-account',
        'uses'  =>  'AccountController@manage'
    ));

    Route::post('/account/{id}/toggle-emails', array(
        'as'    =>  'toggle-emails',
        'uses'  =>  'AccountController@toggle_emails'
    ));

    Route::post('/account/{id}/change-email', array(
        'as'    =>  'change-email',
        'uses'  =>  'AccountController@change_email'
    ));

    Route::post('/account/{id}/change-password', array(
        'as'    =>  'change-pw',
        'uses'  =>  'AccountController@change_pw'
    ));

    Route::post('/account/{id}/change-pic', array(
        'as'    =>  'change-pic',
        'uses'  =>  'AccountController@change_pic'
    ));

    // Editor routes
    Route::post('add-class', array(
        'as'    =>  'e-add-class',
        'uses'  =>  'ScheduleController@e_add_class'
    ));

    Route::post('remove-class', array(
        'as'    =>  'e-remove-class',
        'uses'  =>  'ScheduleController@e_remove_class'
    ));

    //ticket manager routes
    Route::get('/tickets/{schedule_id}', array(
        'as'    => 'ticket-manager',
        'uses'  => 'TicketController@load_ticket_manager'
    ));

    Route::post('/tickets/pullschedule/{schedule_id}', array(
        'as'    => 'ticket-pull-schedule',
        'uses'  => 'TicketController@load_schedule'
    ));

    Route::post('/tickets/add-ticket', array(
        'as'    => 'ticket-add',
        'uses'  => 'TicketController@add_ticket'
    ));

    Route::post('/tickets/resolve-all', array(
        'as'    => 'ticket-resolve-all',
        'uses'  => 'TicketController@resolve_all_for_event'
    ));

    Route::post('/tickets/resolve', array(
        'as'    => 'ticket-resolve',
        'uses'  => 'TicketController@resolve'
    ));

    Route::get('vis', 'VisController@index');
    Route::get('vis/{id0}/{id1}', 'VisController@getData');
    //Route::resource('vis', 'VisController', ['only' => ['index', 'show']]);
});
