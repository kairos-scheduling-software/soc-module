@include('blocks/page_start')
@include('blocks/header')
<div class="hg-columns">
    <div class="hg-col" id="hg-center">
        <div class="top-buffer"></div>
        @yield('center-column')
    </div>
</div>
<footer>
    @include('blocks/footer')
</footer>

@include('blocks/page_end')
