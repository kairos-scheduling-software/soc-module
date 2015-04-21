@extends('layouts.logo-only-header-main')
@section('content')
	<div class="col-md-3"></div>
	<div class="col-md-6">
		<h1>
			Password Recovery
		</h1>

		<form action="{{URL::Route('forgot-pass-post')}}" method="post">
			<input type="text" class="form-control" style="width:250px!important;" name="email" value ="{{Input::old('email')}}" placeholder="Enter Email">
			<div class='account-error'>
				@if($errors->has('email'))
					{{$errors->first('email')}}
				@endif
			</div></br>
			<button type="submit" class="btn btn-forgot">Recover Password</button>
		</form>

		@if(Session::has('global'))
		<div class="global">
			{{Session::get('global')}}
		</div></br>
		@endif
	</div>
	<div class="col-md-3"></div>
@stop