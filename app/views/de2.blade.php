@extends('layouts.main')

@section('content')

<div class="container">
	<div class="top-buffer"></div>
	<div class="data-entry-header">
		<h1>Data Entry</h1>
		<img src="{{ URL::asset('assets/images/prog_02.png') }}"/>
	</div>
	<span id="prog-label-2">Resources</span>
	<div class="data-entry-body">
		<div class="tabs">
			<div class="tab-labels">
				<span class="active">ROOMS</span><span class="inactive">PROFESSORS</span>
			</div>
			<div class="room-listing-headings">
				<div class="room-listing-name">
					NAME
				</div>
				<div class="room-listing-capacity">
					CAPACITY
				</div>
				<div class="room-listing-actions">
					ACTIONS
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
		</div>
	</div>
</div>

@stop