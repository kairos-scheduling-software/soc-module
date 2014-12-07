@include('blocks/page_start')
@include('blocks/header')
<div class="hg-columns">
	<div class="hg-col hg-sidebar" id="hg-left">
		<div id="hg-left-content">
			<div class="top-buffer"></div>
			@yield('left-column')
		</div>
	</div>
	<div class="hg-col" id="hg-center">
		<div class="top-buffer"></div>
		@yield('center-column')	
	</div>
	<div class="hg-col hg-sidebar" id="hg-right">
		<div id="hg-right-content">
			@yield('right-column')	
		</div>
	</div>
</div>
<footer>
	@include('blocks/footer')
</footer>

@include('blocks/page_end')