@foreach($schedules as $schedule)
	<a href="#" class="select-sched" id="{{ 'row_' . $schedule->id }}">
		<div class="sched-list-row" data-url="{{ URL::route('sched-admin', $schedule->id) }}">
			<hr>		
			<div class="sched-name-2">
				{{ $schedule->name }}
			</div>
			<div class="semester-col">
				{{ $schedule->semester }}
			</div>	
			<div class="year-col">
				{{ $schedule->year }}
			</div>	
			<div class="last-edited">
				{{ $schedule->last_edited() }}
			</div>	
		</div>
	</a>
@endforeach