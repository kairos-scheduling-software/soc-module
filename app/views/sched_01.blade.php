@extends('layouts.holy-grail-2col')

@section('left-column')

<h2>{{ $schedule->name }}</h2>
<div class="sched-view-controls">
	<h3>View:</h3>
	<select class="form-control view-select">
		<option>Full Schedule</option>
	</select>
	<h3>Classes:</h3>
	<div id="view-class-list">
		@foreach($schedule->events as $event)
			<div class="view-class-list-row">
				<a href="#" class="event-view-link" data-name="{{ $event->name }}">{{ $event->name }}</a><br>
			</div>
			<hr>
		@endforeach
	</div>
</div>
@stop

@section('center-column')
	<div id="vis-wrapper">
		<h1>{{ $schedule->name }}</h1>
		<div id="vis-container">
		<div id="d3-vis">
	        <div id='d3'></div>

	        <!-- Reusable fake popover for all d3 elements -->
	        <div id='po-d3' class="popover right">
	            <div id='po-d3-arrow' class="arrow"></div>
	            <div class="popover-title">
	                <b><span id='po-d3-name'></span> - <span id='po-d3-title'></span></b>
	                <button id='po-d3-close' type="button" class="close" aria-hidden="true">×</button>
	            </div>
	            <div class="popover-content">
	                <div class="container-fluid">
	                    <form role="form" class="form-horizontal">
	                        <div class="row">
	                            <b>Day/Time:</b> <span class="pull-right" id='po-dtm'></span>
	                            <br/>
	                            <b>Room:</b> <span class="pull-right" id='po-room'></span>
	                        </div>
	                        <br/>
	                        <div class="row">
	                            <label for="hConst">Hard Constraints</label>
	                            <select id="hConst" multiple class="form-control"></select>
	                        </div>
	                        <br/>
	                        <div class="row">
	                            <label for="sConst">Soft Constraints</label>
	                            <select id="sConst" multiple class="form-control"></select>
	                        </div>
	                        <br/>
	                        <div class="row">
	                            <label>Add Constraint(s)</label><br/>
	                            <div class="col-md-6">
	                                <div class="form-group">
	                                    <select class="form-control" id="addConst">
	                                        <option>Avoid Time Conflict</option>
	                                    </select>
	                                </div>
	                            </div>
	                            <div class="col-md-4">
	                                <div class="form-group">
	                                    <select class="form-control" id="addClass"></select>
	                                </div> 
	                            </div>
	                            <div class="col-md-1 pull-right">
	                                <div class="form-group">
	                                    <button class="btn btn-default" id="po-add" type="button">+</button>
	                                </div> 
	                            </div>
	                            <div class="col-md-12">
	                                <div class="form-group">
	                                    <label><input type="checkbox" id="hsCb">Hard Constraint</label>
	                                </div>
	                            </div>
	                            <button id='po-d3-ok' type="button" class="btn btn-default" data-container="body">OK</button>
	                        </div>
	                    </form>
	                </div>
	            </div>
	        </div>
	    </div>
	</div>
</div>
@stop