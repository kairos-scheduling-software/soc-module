@extends ('layouts.holy-grail')

	@section('left-column')
	<h2>Professor Admin</h2>
		<p>
			Professor manager.
		</p>
		<hr>
		<div id="admin-section">
			<p>
				<button id="add-prof-btn" class="btn btn-admin">
					{{ FA::icon('plus') }}&nbsp;&nbsp;
					Add new professor
				</button>
			</p>
			<div id="prof-added">{{ FA::icon('check') }}&nbsp;&nbsp; Professor added</div>
		</div>
		<hr>
		<form id="new-prof-form" data-url="{{ URL::route('add-prof') }}">
			<div class="new-prof-label">Professor Name</div>
			<input id="new-prof-name" class="new-prof-content form-control" name="name" placeholder="Name required" type="text" required></input>
			<div class="new-prof-label">UnID</div>
			<input id="new-prof-uid" class="new-prof-content form-control" name="uid" placeholder="u0000000" type="text"></input>
			<button id="prof-submit-btn" class="btn new-prof-btn btn-primary">Save</button>
			<button id="prof-cancel-btn" class="btn new-prof-btn btn-default">Cancel</button>
			<hr/>
		</form>
	@stop

	@section('center-column')
		<div id="content">
			<div class="content-header">
				<div class="left">
					<h1 style="display:inline-block">Professors</h1>
				</div>
			</div>
			<div id="prof-list" data-list-url="{{ URL::route('prof-list') }}" data-edit-url="{{ URL::route('edit-prof') }}">
				<div class="prof-del col-header"></div>
				<div class="prof-name col-header">
					Name
				</div>
				<div class="prof-uid col-header">
					UnID
				</div>
				<span id="prof-list-data">
				</span>
				<hr>
			</div>
		</div>
		<div id="center-slide-anchor"></div>
		<div class="footer-space"></div>
	@stop
	
@stop
