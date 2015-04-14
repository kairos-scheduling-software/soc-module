<div class="close-panel">
	<a href="#" id="close-btn">&times;</a>
</div>
<h2>{{ $sched->name }}</h2>
<div class="sched-admin-section">
	<h3>{{ FA::icon('file-text-o')}}&nbsp;Description</h3>
	<div id="description-field" data-url="{{ URL::route('description-update', $sched->id)}}">
		@if($sched->description)
			<div id="edit-desc" value="{{$sched->description}}" class="edit-description" onclick="add_description('edit-desc')">{{ $sched->description }}</div>
		@else
			<div id="edit-desc" value="" class="edit-description" onclick="add_description('edit-desc')">Add&nbsp;a&nbsp;description&nbsp;{{ FA::lg('plus')}}</div>
		@endif
	</div>
</div>
<hr>
<div class="sched-admin-section">
	<h3>{{ FA::icon('cog')}}&nbsp;Actions</h3>
	<button class="btn btn-small sched-action-btn" id="view-sched-btn" 
	 		data-url="{{ URL::route('view-sched') . '?id=' . $sched->id . '&dash=3'}}">
		{{ FA::icon('eye')}}&nbsp;VIEW
	</button>
	<button class="btn btn-small sched-action-btn" data-url="{{ URL::route('edit-sched', $sched->id) }}">
		{{ FA::icon('edit')}}&nbsp;EDIT
	</button>
	<button class="btn btn-small" id="copy-sched-btn" data-url="{{ URL::route('branch-sched', $sched->id) }}">
		{{ FA::icon('copy')}}&nbsp;COPY
	</button>
	<button class="btn btn-small" id="delete-sched-btn" 
			data-url="{{ URL::route('delete-sched', $sched->id) }}" 
			data-row="#row_{{ $sched->id }}">
		{{ FA::icon('times')}}&nbsp;DELETE
	</button>
	{{--
	<br><br>
	<a href="#">More {{FA::icon('chevron-right')}}</a>
	--}}
</div>
<hr>
<div class="sched-admin-section">
	<?php
		$tickets = $sched->tickets(); 
		$hasTickets = count($tickets);
		$i = 5;
	?>
	<h3>{{ FA::icon('support') }}&nbsp;
		Support Tickets
		@if($hasTickets)
			<span class="badge" style="margin-top: -5px">{{ $hasTickets }}</span>
		@endif
	</h3>
	@if($hasTickets)
		<table style="margin-bottom: 20px; margin-top: 20px">
			<tr>
				<th>Class</th>
				<th>Comment</th>
			</tr>
		@foreach($tickets as $ticket)
			@if($i > 0)
				<tr>
					<td style="padding-top: 6px; padding-bottom: 6px; padding-right: 15px; white-space: nowrap">{{ $ticket->name }}</td>
					<td>{{ strlen($ticket->message) > 80 ? substr($ticket->message, 0, 79) . "..." :  $ticket->message}}</td>
				</tr>
				<?php $i--; ?>
			@endif
		@endforeach
		@if($hasTickets > 5)
			<td><small>+ {{ ($hasTickets - 5) }} more...</small></td>
		@endif
		</table>
		
		<a href="{{URL::route('ticket-manager', $sched->id)}}">Go to Ticket Manager {{ FA::icon('arrow-circle-right') }}</a>
	@else
		No open tickets for this schedule
	@endif
</div>