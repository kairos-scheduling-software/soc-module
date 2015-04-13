<nav class="navbar navbar-default navbar-fixed-top" role="navigation" id="custom_navbar">
	{{-- Brand and toggle get grouped for better mobile display --}}
	<div class="navbar-header">
		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
		<a class="navbar-brand" href="{{ URL::route('home') }}">
			<img src="{{ URL::asset('assets/images/kairos_cropped.png') }}" id="logo_image"></img>
		</a>
	</div>

	{{-- Collect the nav links, forms, and other content for toggling --}}
	{{-- TODO: fix bug where navbar breaks onto multiple lines during collapse --}}
	{{-- TODO: make navbar line-height shorter if collapsed --}}
	<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
		<ul class="nav navbar-nav navbar-right">
			<li>
				@if(Auth::check())
					<a href="#manage-account" data-toggle="dropdown" class="dropdown-toggle">
						{{ FA::icon('user') . ' Welcome, ' . Auth::user()->first . '!' }}
						<b class="caret"></b>
					</a>
					<ul class="dropdown-menu">
						<li>
							<a href="{{ URL::route('manage-account') }}">
								{{ FA::icon('cogs') }}&nbsp;Manage Account
							</a>
						</li>
					</ul>
				@else
					<a>{{ FA::icon('user') }} Welcome, Guest!</a>
				@endif
			</li>
			<li>
				@if(Auth::check())
					<a href='{{ URL::route('logout') }}'>{{ FA::icon('sign-out') }} Log Out</a>
				@else
					<a href='{{ URL::route('getLogin') }}'>{{ FA::icon('sign-in') }} Login</a>
				@endif
			</li>
		</ul>
	</div><!-- /.navbar-collapse -->
</nav>