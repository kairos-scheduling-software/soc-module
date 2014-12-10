@include('blocks/page_start')
@include('blocks/header')

<div class="page-content">
	@yield('content')
</div>
<div id="bottom-buffer"></div>
@include('blocks/footer')
@include('blocks/page_end')