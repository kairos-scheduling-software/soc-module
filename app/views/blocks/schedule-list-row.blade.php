<a href="#" class="select-sched">
	<div class="sched-list-row" data-url="{{ URL::route('sched-admin', $schedule->id) }}">
		<hr>		
		<div class="sched-name-2">
			{{ $schedule->name }}
		</div>
		<div class="last-edited">
			{{ $schedule->updated_at }}
		</div>
		<div class="edited-by">
			{{ User::find($schedule->last_edited_by)->full_name() }}
		</div>		
	</div>
</a>