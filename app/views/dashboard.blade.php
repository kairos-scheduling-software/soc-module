@extends ('layouts.holy-grail')

	@section('left-column')
	<h2>Schedule Admin</h2>
		<p>
			Choose a schedule to see details.  From there, you can add/remove users, view, edit, copy, or delete the schedule.
		</p>
		<hr>
		<h2>New Schedule</h2>
		<p>
			<button class="btn new-sched">
				{{ FA::icon('plus') }}&nbsp;&nbsp;
				Create
			</button>
			<button class="btn new-sched">
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
			@if($schedules == null)
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
		<h2>Spring 2014</h2>
		<div class="sched-admin-section">
			<h3>{{ FA::icon('file-text-o')}}&nbsp;Description</h3>
			<a href="#">Add&nbsp;a&nbsp;description&nbsp;{{ FA::lg('plus')}}</a>
		</div>
		<hr>
		<div class="sched-admin-section">
			<h3>{{ FA::icon('cog')}}&nbsp;Actions</h3>
			<button class="btn btn-small">
				{{ FA::icon('eye')}}&nbsp;VIEW
			</button>
			<button class="btn btn-small">
				{{ FA::icon('download')}}&nbsp;EXPORT
			</button>
			<button class="btn btn-small">
				{{ FA::icon('copy')}}&nbsp;COPY
			</button>
			<button class="btn btn-small">
				{{ FA::icon('times')}}&nbsp;DELETE
			</button>
		</div>
		<hr>
		<div class="sched-admin-section">
			<h3>{{ FA::icon('group')}}&nbsp;Users</h3>
			<p><span class="red-x">{{ FA::icon('times-circle')}}</span>&nbsp;&nbsp;Kelly Olson</p>
			<p><span class="red-x">{{ FA::icon('times-circle')}}</span>&nbsp;&nbsp;Joe Zachary</p>
			<p><span class="red-x">{{ FA::icon('times-circle')}}</span>&nbsp;&nbsp;Jim de St. Germain</p>
			<a href="#">Add&nbsp;a&nbsp;user&nbsp;{{ FA::lg('plus')}}</a>
		</div>
		<hr>
		<div class="sched-admin-section">
			<h3>{{ FA::icon('flag') }}&nbsp;Proposed Changes</h3>
		</div>
	@stop
	
@stop