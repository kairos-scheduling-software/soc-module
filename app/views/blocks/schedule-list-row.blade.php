<div class="sched-list-row">
	<hr>
	<div class="sched-name">
		{{ $sched_names[$i]["name"] }}
	</div>
	<div class="sched-actions">
		<div class="sched-action">
			<button class="btn">
				{{ FA::lg('eye') }}&nbsp;
				View
			</button>
		</div>
		<div class="sched-action">
			<button class="btn">
				{{ FA::lg('edit') }}&nbsp;
				Edit
			</button>
		</div>
		<div class="sched-action">
			<button class="btn">
				{{ FA::lg('group') }}&nbsp;
				Users
			</button>
		</div>
		<div class="sched-action">
			<button class="btn">
				{{ FA::lg('times') }}&nbsp;
				Delete
			</button>
		</div>
	</div>
</div>