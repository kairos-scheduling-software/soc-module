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
	                    <form action="{{URL::route('ticket-add')}}" role="form" class="form-horizontal" id="log_ticket">
	                        <div class="row">
	                            <b>Day/Time:</b> <span class="pull-right" id='po-dtm'></span>
	                            <br/>
	                            <b>Room:</b> <span class="pull-right" id='po-room'></span>
	                        </div>
	                        <br/>
	                        <div>
	                        	<input id="event_id" class="hide" value="555"></input>
	                        	<div>
	                        		Message: 
	                        		<textarea id="message" class="messageBox" value=""></textarea>
	                        		<button id="submit" name="submit" type="submit" class="btn btn-default">
	                        			Submit Ticket
	                        		</button>
	                        	</div>
	                        </div>
	                    </form>
	                </div>
	            </div>
	        </div>
	    </div>
	</div>
</div>
@stop