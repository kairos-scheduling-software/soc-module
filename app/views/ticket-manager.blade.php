@extends('layouts.main')

@section('content')

<div class="top-buffer"></div>
<div class="ticket-header-container">
	<h1>Ticket Manager</h1>

	Schedule:
	@if(!$schedules->count())
			h2>No schedules have been created</h2>
	@else
		<select id='schedules' name='schedules' onchange="requestSchedule();">
			@foreach($schedules as $schedule)
				<option data-url="{{URL::route('ticket-pull-schedule', $schedule->id)}}" value={{ $schedule->id }} 
					@if($schedule->id == $selected) 
					selected 
				@endif> 
				{{ htmlspecialchars($schedule->name) }} 
			</option>
		@endforeach
	</select>
	@endif
</div>
</br></br>

<div class='ticket-container' id='ticket-container'>
	<div class='ticket-list-header'>
		<div class='ticket-event ticket-header'>
			Class Name
		</div>
		<div class='ticket-event ticket-header'>
			Number of Tickets
		</div>
		<div class='ticket-event ticket-header'>
		</div>
	</div>

	<div id='ticketsTable'>
	</div>
</div>

<div id="resolve_all" class="hide" data-url="{{URL::route('ticket-resolve-all')}}"></div>
<div id="resolve" class="hide" data-url="{{URL::route('ticket-resolve')}}"></div>
@stop