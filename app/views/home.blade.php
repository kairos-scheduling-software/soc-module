<a href='{{ URL::route('register')}}'>register</a></br>
@if(Auth::check())
	{{ Auth::user()->username }}, <a href='{{URL::route('logout')}}'>log out </a>
@else
	guest,<a href='{{ URL::route('getLogin') }}'>login</a>

@endif
