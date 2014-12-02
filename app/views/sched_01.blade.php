@extends('layouts.holy-grail-2col')

@section('left-column')

<h2>Spring 2015</h2>
<div class="sched-view-controls">
	<h3>View:</h3>
	<select class="form-control view-select">
		<option>Full Schedule</option>
	</select>
	<h3 class="large-h3">CS 6770</h3>
	<div class="class-info-header">
		<div>
			<div class="class-key">
				<B>Day/Time: </b>
			</div>
			<div class="class-val">
				T, TH: 2:00 - 3:20
			</div>
		</div>
		<div>
			<div class="class-key">
				<B>Room: </b>
			</div>
			<div class="class-val">
				MEB L122
			</div>
		</div>
	</div>
	<br><br><br>
	<h3>Hard Constraints:</h3>
	<div class="class-list-box">
		@include('blocks/hard-constraint-list')
	</div>
	<h3>Soft Constraints:</h3>
	<div class="class-list-box">
		@include('blocks/soft-constraint-list')
	</div>
	<h3>Add Constraint(s):</h3>
	<select class="form-control view-select">
		<option>Avoid Conflict With</option>
	</select>
	<select class="form-control view-select">
		<option>CS 6610</option>
	</select>
	<button class="btn btn-small" id="btn-add-const">
		<span class="small">{{ FA::icon('plus')}}</span>
	</button><br>
	<input type="checkbox" checked>&nbsp;Hard Constraint</input>

	
</div>
@stop

@section('center-column')
	<h1>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		Spring 2015
	</h1>
	<img src="{{ URL::asset('assets/images/schedule_01.png') }}" id="sched_01_img"/>
@stop