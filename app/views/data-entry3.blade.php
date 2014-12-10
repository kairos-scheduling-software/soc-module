@extends('layouts.data-entry')

@section('prog-bar')
	<img src="{{ URL::asset('assets/images/prog_03.png') }}"/>
@stop

@section('prog-bar-labels')
	<td class="prog-label">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Classes&nbsp;{{FA::icon('check')}}</td>
	<td class="prog-label">Resources&nbsp;{{ FA::icon('check') }}</td>
	<td id="active-prog-label" class="prog-label">&nbsp;&nbsp;&nbsp;Class Constraints</td>
	<td class="prog-label">Resource Constraints&nbsp;&nbsp;&nbsp;</td>
@stop

@section('data-entry-body')
	<div class="room-listing-headings">
		<h1>Classrooms</h1>
		<div class="room-listing-name">
			&nbsp;&nbsp;&nbsp;NAME
		</div>
		<div class="room-listing-capacity">
			CAPACITY&nbsp;&nbsp;
		</div>
		<div class="room-listing-actions">
			ACTIONS&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		</div>
	</div>
	<div class="data-list">
		@for($i = 0; $i < 11; $i++)
			@include('blocks/room-listing')
		@endfor
	</div>
	<div class="add-room-form">
		<form>
			<input type="text" class="form-control room-number-input" placeholder="Room Number..."/>
			<input type="text" class="form-control capacity-input" placeholder="Capacity..."/>
			<button type="submit" class="btn btn-md btn-default add-room-btn">
				Add {{ FA::lg('plus')}}
			</button>
		</form>
	</div>
	<div class="prof-listing-headings">
		<h1>Professors</h1>
		<div class="prof-listing-name">
			NAME
		</div>
		<div class="prof-listing-actions">
			ACTIONS
		</div>
	</div>
	<div class="data-list">
		@for($i = 0; $i < 11; $i++)
			@include('blocks/prof-listing')
		@endfor
	</div>
	<div class="add-prof-form">
		<form>
			<input type="text" class="form-control prof-name-input" placeholder="Professor name..."/>
			<button type="submit" class="btn btn-md btn-default add-prof-btn">
				Add {{ FA::lg('plus')}}
			</button>
		</form>
	</div>
	<div id="next-prev-btns">
		<div class="prev-btn-container">
			<a class="btn" href="{{ URL::route('data-entry2', $schedule->id) }}">
				{{ FA::icon('chevron-left') }}&nbsp;PREVIOUS
			</a>
		</div>
		<div class="next-btn-container">
			<a class="btn" href="{{ URL::route('data-entry3', $schedule->id) }}">
				NEXT&nbsp;{{FA::icon('chevron-right') }}
			</a>
		</div>
	</div>
@stop