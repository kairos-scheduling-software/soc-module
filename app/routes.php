<?php

Route::get('/', array(
        'as' => 'home',
        'uses' => 'HomeController@home'
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

    Route::get('/forgot-pass', array(
            'as'=> 'forgot-pass',
            'uses' => 'AccountController@forgot_pass'
    ));

    Route::post('/forgot-pass-post', array(
            'as'=> 'forgot-pass-post',
            'uses' => 'AccountController@forgot_pass_post'
    ));

    Route::get('/password-reset/{code}', array(
            'as'=> 'password-reset',
            'uses' => 'AccountController@password_reset'
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

    Route::post('/branch-schedule/{sched_id}', array(
        'as' => 'branch-sched',
        'uses' => 'ScheduleController@branch_schedule'
    ));

    Route::post('/description-update/{sched_id}', array(
        'as' => 'description-update',
        'uses' => 'ScheduleController@update_description'
    ));

    Route::post('/update-final-sched/{sched_id}', array(
        'as' => 'update-final-sched',
        'uses' => 'ScheduleController@update_final_sched'
    ));

    Route::post('/sort-sched-list', array(
        'as' => 'sort-sched-list',
        'uses' => 'ScheduleController@sort_sched_list'
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

    Route::post('/import-resources/{mode}', array(
        'as' => 'import-resources',
        'uses' => 'ImportController@import_resources'
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

    Route::post('/account/toggle-emails', array(
        'as'    =>  'toggle-emails',
        'uses'  =>  'AccountController@toggle_emails'
    ));

    Route::post('/account/change-email', array(
        'as'    =>  'change-email',
        'uses'  =>  'AccountController@change_email'
    ));

    Route::post('/account/change-password', array(
        'as'    =>  'change-pw',
        'uses'  =>  'AccountController@change_pw'
    ));

    Route::post('/account/change-pic', array(
        'as'    =>  'change-pic',
        'uses'  =>  'AccountController@change_pic'
    ));

    // Editor routes
    Route::post('/get-schedule', array(
        'as'    =>  'get-sched',
        'uses'  =>  'ScheduleController@get_schedule'
    ));
    Route::post('/edit-schedule', array(
        'as'    =>  'e-edit-schedule',
        'uses'  =>  'ScheduleController@e_edit_schedule'
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

    Route::get('vis/list', 'VisController@index');
    Route::get('vis/{id0}/{id1}/{id3}', 'VisController@getData');
    
    // Move these three outside of 'before'=> 'auth' function to make sched vis links available to the public
    // Start
    Route::get('vis/list/{id0}', 'VisController@index');
    Route::get('vis/{id0}/{id1}', 'VisController@getData');

    Route::get('/view-schedule', array(
        'as'    =>  'view-sched',
        'uses'  =>  'ScheduleController@view_schedule'
    ));
    // End
    
    // Resources manager
    Route::get('/resources/rooms', array(
        'as'    => 'room-manager',
        'uses'  => 'ResourceController@load_room_manager'
    ));
    Route::get('/resources/professors', array(
        'as'    => 'prof-manager',
        'uses'  => 'ResourceController@load_prof_manager'
    ));
    Route::get('/resources/times', array(
        'as'    => 'time-manager',
        'uses'  => 'ResourceController@load_time_manager'
    ));
        
    // Resources route
    Route::post('/resources/get-rooms', array(
        'as'    => 'room-list',
        'uses'  => 'ResourceController@get_rooms'
    ));
    Route::post('/resources/get-room-groups', array(
        'as'    => 'room-group-list',
        'uses'  => 'ResourceController@get_room_groups'
    ));
    Route::post('/resources/add-room', array(
        'as'    => 'add-room',
        'uses'  => 'ResourceController@add_room'
    ));
    Route::post('/resources/add-room-group', array(
        'as'    => 'add-room-group',
        'uses'  => 'ResourceController@add_room_group'
    ));
    Route::post('/resources/edit-room', array(
        'as'    => 'edit-room',
        'uses'  => 'ResourceController@edit_room'
    ));
    Route::post('/resources/edit-room-group', array(
        'as'    => 'edit-room-group',
        'uses'  => 'ResourceController@edit_room_group'
    ));
    
    Route::post('/resources/get-profs', array(
        'as'    => 'prof-list',
        'uses'  => 'ResourceController@get_professors'
    ));
    Route::post('/resources/add-prof', array(
        'as'    => 'add-prof',
        'uses'  => 'ResourceController@add_professor'
    ));
    Route::post('/resources/remove-prof', array(
        'as'    => 'edit-prof',
        'uses'  => 'ResourceController@edit_professor'
    ));
    
    Route::post('/resources/get-times', array(
        'as'    => 'time-list',
        'uses'  => 'ResourceController@get_times'
    ));
    Route::post('/resources/get-time-groups', array(
        'as'    => 'time-group-list',
        'uses'  => 'ResourceController@get_time_groups'
    ));
    Route::post('/resources/import-times', array(
        'as'    => 'import-times',
        'uses'  => 'ResourceController@import_times'
    ));
    Route::post('/resources/add-time', array(
        'as'    => 'add-time',
        'uses'  => 'ResourceController@add_time'
    ));
    Route::post('/resources/add-time-group', array(
        'as'    => 'add-time-group',
        'uses'  => 'ResourceController@add_time_group'
    ));
    Route::post('/resources/remove-time', array(
        'as'    => 'edit-time',
        'uses'  => 'ResourceController@archive_time'
    ));
    Route::post('/resources/edit-time-group', array(
        'as'    => 'edit-time-group',
        'uses'  => 'ResourceController@edit_time_group'
    ));
});
