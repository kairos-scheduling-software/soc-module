@extends('layouts.holy-grail-1col')

@section('top-center-column') 
<div id="center-slide-anchor"></div>
<div id="first-slide"></div>
<div id="vis-wrapper" data-auth-status="{{Auth::check()}}">
    <div id="vis-container">
        <div id="d3-vis">
            <div id="left-nav">
                <div class="title">Visualizations</div>
                @if(Auth::check())
                <ul id="vis-menu-list" class="list">
                    <li class="list-item" title="Total class counts by year/semester and class counts by professor based on a user selected year/semester">
                        <div class="item-content" data-href="#dashboard/1">
                            <i class="glyphicon glyphicon-align-right gly-rotate-90"></i>
                            <h3>Class Counts</h3>
                            <p>Year/Semester</p>
                        </div>
                    </li>
                    <li class="list-item" title="Class counts per hour/day based on a user selected schedule">
                        <div class="item-content" data-href="#dashboard/2">
                            <i class="glyphicon glyphicon-fire"></i>
                            <h3>Heat Maps</h3>
                            <p>Year/Semester</p>
                        </div>
                    </li>
                    <li class="list-item" title="Visualization of a schedule and schedule diff checker">
                        <div class="item-content" data-href="#dashboard/3">
                            <i class=" glyphicon glyphicon-align-center gly-rotate-90"></i>
                            <h3>Schedule Explorer</h3>
                            <p>Year/Semester</p>
                        </div>
                    </li>
                </ul>
                @endif
            </div>

            <div id="content"></div>
        </div>

        <div id='d3'></div>
    </div>
    <script src="assets/vis/js/bootstrap-multiselect.js" type="text/javascript"></script>
    <script src="assets/vis/js/fastclick.js" type="text/javascript"></script>
    <script src="assets/vis/js/d3.v3.min.js" type="text/javascript"></script>
    <script src="assets/vis/js/d3-tip-0.6.6.js" type="text/javascript"></script>
    <script src="assets/vis/js/dimple.mod.min.js" type="text/javascript"></script>
    <script src="assets/vis/js/viewportSize.js" type="text/javascript"></script>
    <script src="assets/vis/js/chroma.min.js" type="text/javascript"></script>
    <script src="assets/vis/js/spin.min.js" type="text/javascript"></script>
    <script src="assets/vis/js/dashboard1.js" type="text/javascript"></script>
    <script src="assets/vis/js/dashboard2.js" type="text/javascript"></script>
    <script src="assets/vis/js/dashboard3.js" type="text/javascript"></script>
    <script src="assets/vis/js/grid.js" type="text/javascript"></script>
    <script src="{{ URL::asset('assets/js/vis-tutorial.js') }}"></script>

</div>
@stop
