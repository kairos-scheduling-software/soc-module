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
			<form id="avatar-form" action="{{ URL::route('change-pic', Auth::user()->id) }}">
				<span class="hide"><input type="file" id="fileInput" name="pic"></span>
				<button class="btn btn-primary btn-sm" id="change-avatar-btn">
					Change
				</button>
			</form>
		</h3>
		<img class="img-circle avatar" width="200" src="{{ Auth::user()->avatar ? 
												  URL::asset('profile_pics/' . Auth::user()->avatar) : 
												  URL::asset('assets/images/default_user_pic.png') }}" />
		<div id="update-image-container">
			<span id="updating-pic">{{ FA::spin('spinner') }} Updating...</span>
			<span id="updated-pic"><big>{{ FA::icon('check') }}</big></span>
		</div>
	</div>
	<div class="col-md-6">
		<div class="row">
			<h3>
				Email Settings
				<span id="updating-email"><small>{{ FA::spin('spinner')}} Updating...</small></span>
				<span id="updated-email"><small>{{ FA::icon('check')}}</small></span>
			</h3>
			<form id="update-email-form" action="{{ URL::route('change-email', Auth::user()->id) }}">
				<div>
					<input type="email" name="email" class="form-control" placeholder="Email Address..." 
						   value="{{ Auth::user()->email }}"/>
				</div>
				<div class="row" id="email-checkbox-container">
					<div class="col-md-8" id="toggle-mails-container">
						{{ Form::checkbox('send_mail', 1, Auth::user()->send_email, ['id' => 'send_mail']) }}
						{{ Form::label('send_mail', 'Send me email notifications') }}
					</div>
					<div class="col-md-4 align-right">
						<button class="btn btn-primary btn-sm" id="change-avatar-btn">
							Update Email
						</button>		
					</div>
				</div>
			</form>
		</div>
		<div class="row" id="change-pw-container">
			<h3>
				Change Password
				<span id="updating-pw"><small>{{ FA::spin('spinner')}} Updating...</small></span>
				<span id="updated-pw"><small>{{ FA::icon('check')}}</small></span>
			</h3>
			<form id="update-pw-form" action="{{ URL::route('change-pw', Auth::user()->id) }}">
				<div class="form-group">
					<input type="password" class="form-control" 
						   placeholder="Current Password..." name="old_pw"/>
				</div>
				<div class="form-group">
					<input type="password" class="form-control" 
						   placeholder="New Password..." name="new_pw"/>
				</div>
				<div class="form-group">
					<input type="password" class="form-control" 
						   placeholder="Confirm New Password..." name="conf_new_pw"/>
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