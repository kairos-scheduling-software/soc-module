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
		<h3 class="right-h3">Rooms:</h3>
		<div class="data-list3">
			@for($i = 0; $i < 30; $i++)
				@include('blocks/class-listing-simple')
			@endfor
		</div>
		<h3 class="right-h3">Professors:</h3>
		<div class="data-list3">
			@for($i = 0; $i < 30; $i++)
				@include('blocks/class-listing-simple')
			@endfor
		</div>
		<div class="constraints">
			<h3 class="large-h3">CS 3500</h3>
			<h3>Hard Constraints:</h3>
			<div class="const-list">
				@for($i = 0; $i < 4; $i++)
					@include('blocks/hard-constraint-listing')
				@endfor
			</div>
			<h3>Soft Constraints:</h3>
			<div class="const-list">
				@for($i = 0; $i < 4; $i++)
					@include('blocks/soft-constraint-listing')
				@endfor
			</div>
			<h3>Add Constraints:</h3>
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