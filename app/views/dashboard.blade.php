@extends ('layouts.holy-grail')

	@section('left-column')
	<script src="{{ URL::asset('assets/js/dash-tutorial.js') }}"></script>
	<h2>Schedule Admin</h2>
		<p>
			Choose a schedule to see details.  From there, you can add/remove users, view, edit, copy, or delete the schedule.
		</p>
		<hr>
		<div id="create-sched-section">
			<h2>New Schedule</h2>
			<p>
				<button class="btn new-sched" id="create-sched-btn" data-url="{{ URL::route('create-sched') }}">
					{{ FA::icon('plus') }}&nbsp;&nbsp;
					Create
				</button>
				<button class="btn new-sched" onclick="window.location='{{ url("import-schedule") }}'">
					{{ FA::icon('cloud-upload') }}&nbsp;&nbsp;
					Import
				</button>
			</p>
		</div>
		<hr>
		<div id="comp-sched-section">
			<h2>Compare Schedules</h2>
			<p>
				Use this tool if you need to see the differences between two of your saved schedules.
			</p>
			<p>
				<button class="btn btn-compare" onclick="window.location='{{ URL::route('view-sched') . '?dash=4'}}'">
					{{ FA::icon('calendar'); }}
					{{ FA::rotate90('sort'); }}
					{{ FA::icon('calendar'); }}
					&nbsp;&nbsp;Compare
				</button>
			</p>
		</div>
		<hr>
		@if($is_admin)
		<div id="resource-mngmnt-section">
			<h2>Administrative</h2>
			<p>
				Rooms and Professors management.
			</p>
			<p>
				<button class="btn btn-resource" onclick="window.location='{{ URL::route("room-manager") }}'">
					{{ FA::icon('th'); }}
					&nbsp;&nbsp;Rooms
				</button>
				<button class="btn btn-resource" onclick="window.location='{{ URL::route("prof-manager") }}'">
					{{ FA::icon('user'); }}
					&nbsp;&nbsp;Professors
				</button>
				<button class="btn btn-resource" onclick="window.location='{{ URL::route("time-manager") }}'"
				style="margin-top: 4px;">
					{{ FA::icon('clock-o'); }}
					&nbsp;&nbsp;Time blocks
				</button>
			</p>
		</div>
		@endif
	@stop

	@section('center-column')
		<div id="schedules-list">
			<div class="sched-list-header">
				<div class="left">
					<h1>Schedules</h1>
				</div>
			</div>
			<div id="sched-list" class="sched-list" data-url="{{URL::route('sort-sched-list')}}">
				@if(!$schedules->count())
					<h2>No schedules have been created</h2>
					You can create or import a new schedule by clicking the appropriate button on the left column.
				@else
					<div id="header_name_col" class="sched-name-2 sched-list-col-header" up='false'>
						Name <i class="fa fa-sort-desc"></i>
					</div>
					<div id="header_semester_col" class="semester-col sched-list-col-header" up='false'>
						Semester <i class="fa fa-sort-desc"></i>
					</div>
					<div id="header_year_col" class="year-col sched-list-col-header" up='false'>
						Year <i class="fa fa-sort-desc"></i>
					</div>
					<div id="header_edit_col" class="last-edited sched-list-col-header" up='false'>
						Last Edited <i class="fa fa-sort-desc"></i>
					</div>
					<span id="schedules_list_data">	
						@include('blocks/schedule-list-row')
					</span>
					<hr>
				@endif

			</div>
		</div>
		<div id="center-slide-anchor"></div>
		<div class="footer-space"></div>
	@stop

	@section('right-column')
	<div class="top-buffer"></div>
	<div id="loading-admin-panel">
		<h2>LOADING&nbsp;{{ FA::spin('spinner') }}</h2>
	</div>
	<div id="ajax-admin-target"></div>
	@stop
	
@stop
