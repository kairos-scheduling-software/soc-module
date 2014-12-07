<div class="top-buffer"></div>
<div class="close-panel">
	<a href="#" id="close-btn">&times;</a>
</div>
<h2>{{ $sched->name }}</h2>
<div class="sched-admin-section">
	<h3>{{ FA::icon('file-text-o')}}&nbsp;Description</h3>
	@if($sched->description)
		{{ $sched->description }}
	@else
		<a href="#">Add&nbsp;a&nbsp;description&nbsp;{{ FA::lg('plus')}}</a>
	@endif
</div>
<hr>
<div class="sched-admin-section">
	<h3>{{ FA::icon('cog')}}&nbsp;Actions</h3>
	<button class="btn btn-small" id="view-sched-btn" data-url="{{ URL::route('view-sched') . '?id=' . $sched->id }}">
		{{ FA::icon('eye')}}&nbsp;VIEW
	</button>
	<button class="btn btn-small">
		{{ FA::icon('download')}}&nbsp;EXPORT
	</button>
	<button class="btn btn-small">
		{{ FA::icon('copy')}}&nbsp;COPY
	</button>
	<button class="btn btn-small">
		{{ FA::icon('times')}}&nbsp;DELETE
	</button>
</div>
<hr>
<div class="sched-admin-section">
	<h3>{{ FA::icon('group')}}&nbsp;Users</h3>
	@foreach($sched->users as $user)
		<p><span class="red-x">{{ FA::icon('times-circle')}}</span>&nbsp;&nbsp;{{ $user->full_name() }}</p>
	@endforeach
	<a href="#">Add&nbsp;a&nbsp;user&nbsp;{{ FA::lg('plus')}}</a>
</div>
<hr>
<div class="sched-admin-section">
	<h3>{{ FA::icon('flag') }}&nbsp;Proposed Changes</h3>
</div>