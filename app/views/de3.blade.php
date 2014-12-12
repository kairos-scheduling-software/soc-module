@extends('layouts.main')

@section('content')

<div class="container">
	<div class="top-buffer"></div>
	<div class="data-entry-header">
		<h1>Data Entry</h1>
		<img src="{{ URL::asset('assets/images/prog_04.png') }}"/>
	</div>
	<span id="prog-label-4">Resource Constraints</span>
	<div class="data-entry-body2">
		<div class="resource-lists">
		<h3 class="right-h3">Classrooms:</h3>
		<div class="data-list2">
				@include('blocks/class-listing-simple')
		</div>
		<h3 class="right-h3">Professors:</h3>
		<div class="data-list2">
				@include('blocks/class-listing-simple')
		</div>
	</div>
		<div class="constraints">
			<h3 class="large-h3">WEB 1230</h3>
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
	</div>
</div>

@stop