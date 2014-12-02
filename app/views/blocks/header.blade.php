<nav class="navbar navbar-default navbar-fixed-top" role="navigation" id="custom_navbar">
	{{-- Brand and toggle get grouped for better mobile display --}}
	<div class="navbar-header">
		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
		<a class="navbar-brand" href="{{ URL::route('root') }}">
			<img src="{{ URL::asset('assets/images/kairos_cropped.png') }}" id="logo_image"></img>
		</a>
	</div>

	{{-- Collect the nav links, forms, and other content for toggling --}}
	{{-- TODO: fix bug where navbar breaks onto multiple lines during collapse --}}
	{{-- TODO: make navbar line-height shorter if collapsed --}}
	<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
		<ul class="nav navbar-nav">
				<li {{ $page_name == 'HOME' ? 'class="selected_page"' : '' }}>
					<a href="{{ URL::route('root') }}">Home</a>
				</li>
				<li {{ $page_name == 'SCHEDULES' ? 'class="selected_page"' : '' }}>
					<a href="#projects">Schedules</a>
				</li>
				<li {{ $page_name == 'DATATOOLS' ? 'class="selected_page"' : '' }}>
					<a href="#data-tools">Data Tools</a>
				</li>
		</ul>
	
		<ul class="nav navbar-nav navbar-right">
			<li {{ $page_name == 'SETTINGS' ? 'class="selected_page"' : '' }}>
				<a href="#settings">{{ FA::icon('cogs') }} Settings</a>
			</li>
			<li><a href="#logout">Logout</a></li>
		</ul>
	</div><!-- /.navbar-collapse -->
</nav>