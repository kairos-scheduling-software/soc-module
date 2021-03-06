@extends ('layouts.landing')

@section ('content')

<div id="page_content" class="container landing-container">
	<div class="landing-top-buffer"></div>
	<img src="{{ URL::asset('assets/images/kairos_cropped.png') }}" id="landing-logo"/>
	<h1>Welcome to Kairos</h1>
	<div class="intro-text">
		<p>
			This tool is designed to facilitate the creation of class schedules.  You give us
			your data (classes, rooms, professors, etc.) and we generate a schedule that meets
			your constraints.
		</p>
	</div>
	<div class="landing-prompt">
		<p>
			Choose an option to continue:
		</p>
		<a href="http://kairos-api-docs.herokuapp.com" class="btn btn-lg">
			API documentation
		</a>
		<a class="btn btn-lg" data-toggle="modal" href="#login-modal">
			Login
		</a>
		@include('blocks/login-modal')		
	</div>
</div>

@stop