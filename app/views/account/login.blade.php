@if(Session::has('global'))
	<div>
		{{Session::get('global')}}
	</div></br>
@endif




<form action="{{ URL::route('postLogin') }}" method='post'>
	
	username: <input type='text' name='username' value = {{Input::old('username')}}></input>
	@if($errors->has('username'))
		{{$errors->first('username')}}
	@endif
	</br>

	password: <input type='password' name='password'></input>
	@if($errors->has('password'))
		{{$errors->first('password')}}
	@endif
	</br>

	<input type='submit' name='submit' value='submit'></input></br>

</form>