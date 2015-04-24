@include('blocks/page_start')
@include('blocks/header')

<div class="page-content">
	<div id="left-side-bar">
		<div class="top-buffer"></div>
		@yield('left-column')
	</div>
	<div id="main-container">
		<div class="top-buffer"></div>
		@yield('main-column')	
	</div>
	<div id="right-side-bar">
		<div class="top-buffer"></div>
		@yield('right-column')
	</div>
</div>

@include('blocks/footer')
@include('blocks/page_end')