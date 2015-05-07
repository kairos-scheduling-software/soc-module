@extends ('layouts.holy-grail')

@section('head')
<!--Time manager script-->
<script src="{{URL::asset('assets/js/bootstrap-editable.min.js')}}"></script>
<script src="{{URL::asset('assets/js/time-manager.js')}}"></script>

<!--Time manager css-->
<link href="{{URL::asset('assets/css/bootstrap-editable.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/css/time-manager.css')}}" rel="stylesheet">
@stop

	@section('left-column')
	<h2>Time Admin</h2>
	<p>
		Times and time groups manager.
	</p>
	<hr>
	<form id=import-time-form data-url="{{ URL::route('import-times') }}" method="post" enctype="multipart/form-data">
		<input type="file" name="times_file" style="margin: 10px">
		<button class="btn import-time-btn btn-primary">
			{{ FA::icon('plus') }}&nbsp;&nbsp;
			Import Time Blocks
		</button>
	</form>
	<hr>
	<div id="admin-section">
		<p>
			<button id="add-time-btn" class="btn btn-time-admin">
				{{ FA::icon('plus') }}&nbsp;&nbsp;
				Add new time
			</button>
			<button id="add-group-btn" class="btn btn-time-admin editable editable-click" data-url="{{ URL::route('add-time-group') }}">
				{{ FA::icon('plus') }}&nbsp;&nbsp;
				Add new group
			</button>
		</p>
		<div id="time-added">{{ FA::icon('check') }}&nbsp;&nbsp; Time added</div>
	</div>
	<hr>
	<form id="new-time-form" data-url="{{ URL::route('add-time') }}">
		<div class="new-time-label">Days</div>
		<input id="new-time-days" class="new-time-content form-control" name="days" placeholder="Days" type="text" required></input>
		<div class="new-time-label">Start time</div>
		<input id="new-time-start" class="new-time-content form-control" name="start" placeholder="Start time" type="text" required></input>
		<div class="new-time-label">Length</div>
		<input id="new-time-length" class="new-time-content form-control" name="length" placeholder="Length (minutes)" type="text" required></input>
		<button id="time-submit-btn" class="btn new-time-btn btn-primary">Save</button>
		<button id="time-cancel-btn" class="btn new-time-btn btn-default">Cancel</button>
		<hr/>
	</form>
	<div id="time-group-section">
		<h2>Time Groups</h2>
		<span id='time-group-list' style="overflow: auto" data-url="{{ URL::route('time-group-list') }}">
		</span>
	</div>
	@stop

	@section('center-column')
		<div id="content">
			<div class="content-header">
				<div class="left">
					<h1 style="display:inline-block">Times&nbsp;</h1>
					<button id="time-list-edit" class="btn editable editable-click" data-url="{{ URL::route('edit-time-group') }}">
						edit
					</button>
				</div>
			</div>
			<div id="time-list" data-list-url="{{ URL::route('time-list') }}" data-edit-url="{{ URL::route('edit-time') }}">
				<div class="time-del col-header"></div>
				<div class="time-start col-header">
					Start time
				</div>
				<div class="time-length col-header">
					Duration (minutes)
				</div>
				<div class="time-days col-header">
					Days
				</div>
				<span id="time-list-data">
				</span>
				<hr>
			</div>
		</div>
		<div id="center-slide-anchor"></div>
		<div class="footer-space"></div>
	@stop
	
@stop
