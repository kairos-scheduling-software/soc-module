@extends ('layouts.holy-grail')

	@section('left-column')
	<h2>Room Admin</h2>
		<p>
			Rooms and room groups manager.
		</p>
		<hr>
		<div id="admin-section">
			<p>
				<button id="add-room-btn" class="btn btn-room-admin">
					{{ FA::icon('plus') }}&nbsp;&nbsp;
					Add new room
				</button>
				<button id="add-group-btn" class="btn btn-room-admin editable editable-click" data-url="{{ URL::route('add-room-group') }}">
					{{ FA::icon('plus') }}&nbsp;&nbsp;
					Add new group
				</button>
			</p>
			<div id="room-added">{{ FA::icon('check') }}&nbsp;&nbsp; Room added</div>
		</div>
		<hr>
		<form id="new-room-form" data-url="{{ URL::route('add-room') }}">
			<div class="new-room-label">Room Name</div>
			<input id="new-room-name" class="new-room-content form-control" name="name" placeholder="Name required" type="text" required></input>
			<div class="new-room-label">Room Capacity</div>
			<input id="new-room-capacity" class="new-room-content form-control" name="capacity" placeholder="Capacity" type="text"></input>
			<button id="room-submit-btn" class="btn new-room-btn btn-primary">Save</button>
			<button id="room-cancel-btn" class="btn new-room-btn btn-default">Cancel</button>
			<hr/>
		</form>
		<div id="room-group-section">
			<h2>Room Groups</h2>
			<span id='room-group-list' style="overflow: auto" data-url="{{ URL::route('room-group-list') }}">
			</span>
		</div>
	@stop

	@section('center-column')
		<div id="content">
			<div class="content-header">
				<div class="left">
					<h1 style="display:inline-block">Rooms&nbsp;</h1>
					<button id="room-list-edit" class="btn editable editable-click" data-url="{{ URL::route('edit-room-group') }}">
						edit
					</button>
				</div>
			</div>
			<div id="room-list" data-list-url="{{ URL::route('room-list') }}" data-edit-url="{{ URL::route('edit-room') }}">
				<div class="room-del col-header"></div>
				<div class="room-name col-header">
					Name
				</div>
				<div class="room-capacity col-header">
					Capacity
				</div>
				<span id="room-list-data">
				</span>
				<hr>
			</div>
		</div>
		<div id="center-slide-anchor"></div>
		<div class="footer-space"></div>
	@stop
	
@stop
