@include('blocks/page_start')
@include('blocks/logo-only-header')
<div class="top-buffer"></div>
<div class="page-content">
	@yield('content')
</div>
@include('blocks/footer')
@include('blocks/page_end')