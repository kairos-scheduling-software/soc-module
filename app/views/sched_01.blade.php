@extends('layouts.holy-grail-2col')

@section('left-column')

<h2>Spring 2015</h2>
<div class="sched-view-controls">
	<h3>View:</h3>
	<select class="form-control view-select">
		<option>Full Schedule</option>
	</select>	
</div>
@stop

@section('center-column')
	<h1>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		Spring 2015
	</h1>
	<img src="{{ URL::asset('assets/images/schedule_01.png') }}" id="sched_01_img"/>
@stop