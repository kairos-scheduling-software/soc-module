@extends('layouts.main')

@section('content')

<div class="container">
	<div class="top-buffer"></div>
	<div class="data-entry-header">
		<h1>Data Entry</h1>
		<img src="{{ URL::asset('assets/images/prog_01.png') }}"/>
	</div>
	<div id="prog-label-container">
		<table id="progress-labels">
			<tr>
				<td id="active-prog-label" class="prog-label">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Classes</td>
				<td class="prog-label">Resources&nbsp;</td>
				<td class="prog-label">&nbsp;Class Constraints</td>
				<td class="prog-label">Resource Constraints&nbsp;&nbsp;&nbsp;</td>
			</tr>
		</table>
	</div>
	<div class="data-entry-body">
		@if(!($schedule->events()->count()))
			<div id="empty-data-list">
				<h2>No classes have been added yet</h2>
			</div>
			<div class="data-list" style="display:none"></div>
		@else
			<div class="data-list">
				@foreach($schedule->events as $class)
					@include('blocks/class-listing')
				@endforeach
			</div>
		@endif
		<form id="add-class-form" method="post" action="{{ URL::route('add-class', $schedule->id) }}">
			<input type="text" name="name" class="form-control class-name-input" placeholder="Class Name..."/>
			<button type="submit" class="btn btn-md btn-default add-class-btn">
				Add {{ FA::lg('plus')}}
			</button>
		</form>
	</div>
</div>

@stop