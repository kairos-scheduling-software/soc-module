@extends('layouts.data-entry')

@section('prog-bar')
	<img src="{{ URL::asset('assets/images/prog_01.png') }}"/>
@stop

@section('prog-bar-labels')
	<td id="active-prog-label" class="prog-label">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Classes</td>
	<td class="prog-label">Resources&nbsp;</td>
	<td class="prog-label">&nbsp;&nbsp;&nbsp;Class Constraints</td>
	<td class="prog-label">Resource Constraints&nbsp;&nbsp;&nbsp;</td>
@stop

@section('data-entry-body')
	@if(!($schedule->events()->count()))
		<div id="empty-data-list">
			<h2>No classes have been added yet</h2>
		</div>
		<div id="classes-data-list" class="data-list" style="display:none"></div>
	@else
		<div id="empty-data-list" style="display:none">
			<h2>No classes have been added yet</h2>
		</div>
		<div class="data-list" id="classes-data-list">
			@foreach($schedule->events as $class)
				<div class='class-listing-container'>
					@include('blocks/class-listing')
				</div>
			@endforeach
		</div>
	@endif
	<form id="add-class-form" method="post" action="{{ URL::route('add-class', $schedule->id) }}">
		<input type="text" name="name" class="form-control class-name-input" placeholder="Class Name..." required/>
		<button type="submit" class="btn btn-md btn-default add-class-btn">
			Add {{ FA::lg('plus')}}
		</button>
	</form>
	<div id="next-prev-btns">
		<div class="prev-btn-container"></div>
		<div class="next-btn-container">
			<a class="btn" href="{{ URL::route('data-entry2', $schedule->id) }}">
				NEXT&nbsp;{{FA::icon('chevron-right') }}
			</a>
		</div>
	</div>
@stop