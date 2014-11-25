<form action='{{URL::route('register-post')}}' method='post'>
	<span>first name:</span>
	<input type='text' name='first_name' value = {{Input::old('first_name')}} ></input>
	@if($errors->has('first_name'))
		{{$errors->first('first_name')}}
	@endif
	</br>

	<span>last name:</span>
	<input type='text' name='last_name' value = {{Input::old('last_name')}} ></input>
	@if($errors->has('last_name'))
		{{$errors->first('last_name')}}
	@endif
	</br>

	<span>Username:</span>
	<input type='text' name='username' value = {{Input::old('username')}} ></input>
	@if($errors->has('username'))
		{{$errors->first('username')}}
	@endif
	</br>
	
	<span>Email:</span>
	<input type='text' name='email' value = {{Input::old('email')}} ></input>
	@if($errors->has('email'))
		{{$errors->first('email')}}
	@endif
	</br>
	
	<span>Password:</span>
	<input type='password' name='password'></input>
	@if($errors->has('password'))
		{{$errors->first('password')}}
	@endif
	</br>
	
	<span>Confirm Password:</span>
	<input type='password' name='confirm_password'></input>
	@if($errors->has('confirm_password'))
		{{$errors->first('confirm_password')}}
	@endif
	</br>
	
	<input type='submit' value='Register'></input>
</form>