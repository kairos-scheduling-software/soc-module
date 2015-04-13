@extends('layouts.logo-only-header-main')
@section('content')
<div class="col-md-3"></div>
<div class="col-md-6 account-reg-log">
	<div class="col-md-6 account-reg" id="login-form">
		<div class="modal-header">
			Register for Kairos
		</div>
		<div class="account-body">
			<form action='{{URL::route("register-post")}}' method='post'>
				
				<input type='text' name='first_name' placeholder="First Name" value ="{{Input::old('first_name')}}" ></input>
				<div class='account-error'>
					@if($errors->has('first_name'))
						{{$errors->first('first_name')}}
					@endif
				</div>

				<input type='text' name='last_name' placeholder="Last Name" value ="{{Input::old('last_name')}}" ></input>
				<div class='account-error'>
					@if($errors->has('last_name'))
						{{$errors->first('last_name')}}
					@endif
				</div>

				<input type='text' name='username' placeholder="Username" value ="{{($page_name == 'register') ? Input::old('username') : ''}}" ></input>
				<div class='account-error'>
					@if($errors->has('username') and $page_name == 'register')
						{{$errors->first('username')}}
					@endif
				</div>

				<input type='text' name='email' placeholder="Email" value ="{{Input::old('email')}}" ></input>
				<div class='account-error'>
					@if($errors->has('email'))
						{{$errors->first('email')}}
					@endif
				</div>
	
				<input type='password' placeholder="Password" name='password'></input>
				<div class='account-error'>
					@if($errors->has('password') and $page_name == 'register')
						{{$errors->first('password')}}
					@endif
				</div>

				<input type='password' placeholder="Confirm Password" name='confirm_password'></input>
				<div class='account-error'>
					@if($errors->has('confirm_password'))
						{{$errors->first('confirm_password')}}
					@endif
				</div>
	
				<input class="btn account-btn-login" type='submit' value='Register'></input>
			</form>
			@if(Session::has('global'))
			<div class="global">
				{{Session::get('global')}}
			</div></br>
			@endif
		</div>
	</div>
	<div class="col-md-6" id="register-form">
		<div class="modal-header">
			Login to Kairos
		</div>
		<div class="account-body">
			<form method="post" action="{{ URL::route('postLogin') }}" name="login_form">
				<input type="text" name="username" value ="{{($page_name == 'login') ? Input::old('username') : ''}}" placeholder="Username">
				<div class='account-error'>
					@if($errors->has('username') and $page_name=='login')
						{{$errors->first('username')}}
					@endif
				</div>
      			<input type="password" name="password" placeholder="Password">
      			<div class='account-error'>
					@if($errors->has('password') and $page_name=='login')
						{{$errors->first('password')}}
					@endif
				</div>
      			
      			<button type="submit" class="btn btn-login">Log in</button>
      		</form>
      		@if(Session::has('global-login'))
			<div class="global">
				{{Session::get('global-login')}}
			</div></br>
			@endif
		</div>
	</div>
	<div class="col-md-3"></div>
</div>
@stop