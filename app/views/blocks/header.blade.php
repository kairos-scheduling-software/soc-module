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
		<ul class="nav navbar-nav">
				<li {{ $page_name == 'HOME' || $page_name == "DASHBOARD" ? 'class="selected_page"' : '' }}>
					<a id="dash-nav-link" href="{{ URL::route('home') }}">{{ FA::icon('dashboard') }} Dashboard</a>
				</li>
				<li {{ $page_name == 'DATATOOLS' ? 'class="selected_page"' : '' }}>
					<a id="data-tools-nav-link" href="{{URL::route('view-sched')}}">{{ FA::icon('line-chart') }} Data Tools</a>
				</li>
				<li {{ $page_name == 'HELP' ? 'class="selected_page"' : '' }}>
					<a id="help-nav-link" href="#help" data-toggle="dropdown" class="dropdown-toggle">
						{{ FA::icon('question-circle') }} Help
					</a>
					<ul class="dropdown-menu">
						<li>
							<a href="#" id="view-tut-link">View Page Tour</a>
						</li>
					</ul>
				</li>
		</ul>
	
		<ul class="nav navbar-nav navbar-right">
			<li>
				@if(Auth::check())
					<a href="#manage-account"  id="manage-account-nav-link" data-toggle="dropdown" class="dropdown-toggle">
						{{-- FA::icon('user') . ' Welcome, ' . Auth::user()->first . '!' --}}
						<img class="img-circle" width="40" id="avatar" src="{{ Auth::user()->avatar ? 
												  URL::asset('profile_pics/' . Auth::user()->avatar) : 
												  URL::asset('assets/images/default_user_pic.png') }}" />
						&nbsp;Welcome, {{Auth::user()->first . '!'}}
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