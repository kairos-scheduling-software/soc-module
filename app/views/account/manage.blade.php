@extends('layouts.main')

@section('content')

<script>
	var toggle_emails_url = "{{ URL::route('toggle-emails', Auth::user()->id) }}";
	var change_pw_url = "{{ URL::route('change-pw', Auth::user()->id) }}";
</script>
<div class="top-buffer"></div>
<div class="container">
	<h1>Manage Account</h1>
	<div class="col-md-4 row">
		<h3>
			Profile Picture
			<button class="btn btn-primary btn-sm" id="change-avatar-btn">
				Change
			</button>
		</h3>
		<img class="img-circle" width="200" src="https://kriticalmass.com/themes/default/img/profile-no-photo.png"/>
	</div>
	<div class="col-md-6">
		<div class="row">
			<h3>Email Settings</h3>
			<form>
				<div>
					<input type="email" class="form-control" placeholder="Email Address..." 
						   value="{{ Auth::user()->email }}"/>
				</div>
				<div class="row" id="email-checkbox-container">
					<div class="col-md-6">
						{{ Form::checkbox('send_mail', 1, Auth::user()->send_email, ['id' => 'send_mail']) }}
						{{ Form::label('send_mail', 'Send me email notifications') }}
					</div>
					<div class="col-md-6 align-right">
						<button class="btn btn-primary btn-sm" id="change-avatar-btn">
							Update Email
						</button>		
					</div>
				</div>
			</form>
		</div>
		<div class="row" id="change-pw-container">
			<h3>Change Password</h3>
			<form>
				<div class="form-group">
					<input type="password" class="form-control" placeholder="Current Password..." name="old_pw"/>
				</div>
				<div class="form-group">
					<input type="password" class="form-control" placeholder="New Password..." name="new_pw"/>
				</div>
				<div class="form-group">
					<input type="password" class="form-control" placeholder="Confirm New Password..." name="new_pw"/>
				</div>
				<div class="align-right">
					<button class="btn btn-primary btn-sm" id="change-avatar-btn">
						Update Password
					</button>
				</div>
			</form>
		</div>
	</div>
</div>

@stop