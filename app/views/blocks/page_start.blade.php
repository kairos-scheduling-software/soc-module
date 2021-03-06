<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>KAIROS
        <?php
            switch($page_name)
            {
                case "HOME":
                    echo "&nbsp;|&nbsp;Home";
                    break;
                case "SCHEDULES":
                    echo "&nbsp;|&nbsp;Schedules";
                    break;
                case "DATATOOLS":
                    echo "&nbsp;|&nbsp;Data Tools";
                    break;
                case "SETTINGS":
                    echo "&nbsp;|&nbsp;Settings";
                    break;
                default:
                    break;
            }
        ?>
    </title>

    <link rel="shortcut icon" href=" {{ URL::asset('assets/ico/favicon.png') }}">

    <!-- Bootstrap -->
    <link href="{{ URL::asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/css/styles.css') }}" rel="stylesheet">

    <!-- FontAwesome CSS -->
    {{-- FA::css() --}}
    <link href="//netdna.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">

    <!-- Tutorialize CSS -->
    <link href="{{ URL::asset('assets/css/tutorialize.css') }}" rel="stylesheet">

    <!-- Load scripts -->
    @if($page_name == 'Data Entry')
    <script>
        var class_data_count = {{ $schedule->events->count() }};
    </script>
    @endif
    <script src="{{ URL::asset('assets/js/jquery-2.1.1.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/jquery-ui.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/jquery.tutorialize.js') }}"></script>
    <script src="{{ URL::asset('assets/js/bootstrap.min.js') }}"></script>
    @if($page_name == 'Schedule View')
        <link href="{{ URL::asset('assets/vis/css/xcharts.css') }}" rel="stylesheet">
        <link href="{{ URL::asset('assets/vis/css/bootstrap-multiselect.css') }}" rel="stylesheet">
        <link href="{{ URL::asset('assets/vis/css/visualization.css') }}" rel="stylesheet">
    @endif
    @if($page_name == 'SETTINGS')
        <script src="{{ URL::asset('assets/js/account-settings.js') }}"></script>
    @endif
    @if($page_name == 'Schedule Editor')
        <!-- Editor stylesheets -->
        <link href="{{ URL::asset('assets/css/sched-editor.css') }}" rel="stylesheet">
        <link href="{{ URL::asset('assets/css/bootstrap-timepicker.min.css') }}" rel="stylesheet">

        <!-- Editor scripts -->
        <script src="{{ URL::asset('assets/js/jquery-ui.min.js') }}"></script>
        <script src="{{ URL::asset('assets/js/bootstrap-timepicker.min.js') }}"></script>
        <script src="{{ URL::asset('assets/js/sched-editor.js') }}"></script>
        <script>
        $(function() {
            //time_blocks = {{ $time_blocks }};
            //process_time_blocks(time_blocks);
            var rms = {{ json_encode($rooms) }};
            var grps = {{ json_encode($room_groups) }};
            
            room_groups = Object.create(null);
            rooms = rms;
            rooms.sort(function(r1, r2){
                r1 = r1['name'];
                r2 = r2['name'];
                return r1 < r2 ? -1 : (r1 > r2 ? 1 : 0);
            });
            rooms.unshift({id: -1, name: 'All'});
            $.each(grps, function(i, rec) {
                var grp_id = '' + rec['id'];
                var grp_name = rec['name'];
                var grp = room_groups[grp_id] = rec['rooms'];
                //~ $.each(rec['rooms'], function(i, rm) {
                    //~ grp.push(rm['id'], rm['name']);
                //~ });
                //grp = rec['rooms'];
                grp.sort(function(r1, r2){
                    r1 = r1['name'];
                    r2 = r2['name'];
                    return r1 < r2 ? -1 : (r1 > r2 ? 1 : 0);
                });
                grp.unshift({id: -1, name: 'All'});
                grp.name = grp_name;
            });
            
            professors = {{ json_encode($professors) }};
            // schedule_json = { $schedule->to_json() };
            // load_schedule(schedule_json);
        });
        </script>
    @endif
    @if($page_name == 'Ticket Manager')
        <!--Ticket manger script-->
        <script src="{{URL::asset('assets/js/ticket-manager.js')}}"></script>
        <script>
            $(function()
            {
                createTable({{ $tickets }});
            });
        </script>

        <!--Ticket manager css-->
        <link href="{{URL::asset('assets/css/ticket-manager.css')}}" rel="stylesheet">
    @endif
    @if($page_name == 'Import Schedule')
        <script src="{{URL::asset('assets/js/import.js')}}"></script>
    @endif
    @if($page_name == 'login' || $page_name == 'register')
        <link href="{{URL::asset('assets/css/account.css')}}" rel="stylesheet">
    @endif
    @if($page_name == 'Room Manager')
        <!--Room manager script-->
        <script src="{{URL::asset('assets/js/bootstrap-editable.min.js')}}"></script>
        <script src="{{URL::asset('assets/js/room-manager.js')}}"></script>
        
        <!--Room manager css-->
        <link href="{{URL::asset('assets/css/bootstrap-editable.css')}}" rel="stylesheet">
        <link href="{{URL::asset('assets/css/room-manager.css')}}" rel="stylesheet">
    @endif
    @if($page_name == 'Professor Manager')
        <!--Professor manager script-->
        <script src="{{URL::asset('assets/js/bootstrap-editable.min.js')}}"></script>
        <script src="{{URL::asset('assets/js/prof-manager.js')}}"></script>
        
        <!--Professor manager css-->
        <link href="{{URL::asset('assets/css/bootstrap-editable.css')}}" rel="stylesheet">
        <link href="{{URL::asset('assets/css/prof-manager.css')}}" rel="stylesheet">
    @endif

    <script src="{{ URL::asset('assets/js/main.js') }}"></script>
    <script src="{{ URL::asset('assets/js/dashboard.js') }}"></script>



    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    
    @yield('head')
  </head>
  <body id="{{$page_name == 'LANDING' ? 'landing' : 'custom'}}-body">
