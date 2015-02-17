@extends ('layouts.holy-grail')

	@section('left-column')
	<h2>Schedule Admin</h2>
		<p>
			Choose a schedule to see details.  From there, you can add/remove users, view, edit, copy, or delete the schedule.
		</p>
		<hr>
		<h2>New Schedule</h2>
		<p>
			<button class="btn new-sched" id="create-sched-btn">
				{{ FA::icon('plus') }}&nbsp;&nbsp;
				Create
			</button>
			<button class="btn new-sched" onclick="window.location='{{ url("import-schedule") }}'">
				{{ FA::icon('cloud-upload') }}&nbsp;&nbsp;
				Import
			</button>
		</p>
		<hr>
		<h2>Compare Schedules</h2>
		<p>
			Use this tool if you need to see the differences between two of your saved schedules.
		</p>
		<p>
			<button class="btn btn-compare">
				{{ FA::icon('calendar'); }}
				{{ FA::rotate90('sort'); }}
				{{ FA::icon('calendar'); }}
				&nbsp;&nbsp;Compare
			</button>
		</p>
	@stop

	@section('center-column')
		<div class="sched-list-header">
			<div class="left">
				<h1>Schedules</h1>
			</div>
			<div class="right">
				Sort by:&nbsp;<select class="form-control">
					<option>Recently Edited</option>
					<option>Schedule Name</option>
				</select>
			</div>
		</div>
		<div class="sched-list">
			@if(!$schedules->count())
				<h2>No schedules have been created</h2>
				You can create or import a new schedule by clicking the appropriate button on the left column.
			@else
				<div class="sched-name-2 sched-list-col-header">
					Name
				</div>
				<div class="last-edited sched-list-col-header">
					Last Edited
				</div>
				<div class="edited-by sched-list-col-header">
					Edited By
				</div>			
				@foreach($schedules as $schedule)
					@include('blocks/schedule-list-row')
				@endforeach
				<hr>
			@endif

		</div>
	@stop

	@section('right-column')
	<div class="top-buffer"></div>
	<div id="loading-admin-panel">
		<h2>LOADING&nbsp;{{ FA::spin('spinner') }}</h2>
	</div>
	<div id="ajax-admin-target"></div>
	@stop
	
@stop