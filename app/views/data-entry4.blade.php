@extends('layouts.data-entry2')

@section('prog-bar')
	<img src="{{ URL::asset('assets/images/prog_04.png') }}"/>
@stop

@section('prog-bar-labels')
	<td class="prog-label">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Classes&nbsp;{{FA::icon('check')}}</td>
	<td class="prog-label">Resources&nbsp;{{ FA::icon('check') }}</td>
	<td class="prog-label">&nbsp;&nbsp;&nbsp;Class Constraints&nbsp;{{ FA::icon('check') }}</td>
	<td id="active-prog-label" class="prog-label">Resource Constraints&nbsp;&nbsp;&nbsp;</td>
@stop

@section('data-entry-body')
	<div class="resource-lists">
		<h3 class="right-h3">Classes:</h3>
		<div class="data-list5">
			@include('blocks/class-listing-simple')
		</div>
	</div>
	<div class="constraints">
		<h3 class="large-h3">CS 1400</h3>
		<h3>Hard Constraints:</h3>
		<div class="const-list">
			@for($i = 0; $i < 4; $i++)
				@include('blocks/hard-constraint-listing')
			@endfor
		</div>
		<h3>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Soft Constraints:</h3>
		<div class="const-list">
			@for($i = 0; $i < 4; $i++)
				@include('blocks/soft-constraint-listing')
			@endfor
		</div>
		<h3>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Add Constraints:</h3>
		<form class="add-const-form">
			<select class="form-control const-select">
				<option>Avoid Conflict With:</option>
			</select>
			<select class="form-control const-select">
				<option>CS 3700</option>
			</select>
			<button type="submit" class="btn btn-md btn-default add-class-btn">
				Add {{ FA::lg('plus')}}
			</button><br>
			<input type="checkbox" checked>&nbsp;Hard Constraint</input>
		</form>
	</div>
	<div id="next-prev-btns">
		<div class="prev-btn-container">
			<a class="btn" href="{{ URL::route('data-entry2', $schedule->id) }}">
				{{FA::icon('chevron-left') }}&nbsp;PREVIOUS
			</a>
		</div>
		<div class="next-btn-container">
			<a class="btn" href="{{ URL::route('data-entry4', $schedule->id) }}">
				FINISH&nbsp;{{FA::icon('chevron-right') }}
			</a>
		</div>
	</div>
@stop