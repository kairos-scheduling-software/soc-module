@extends('layouts.holy-grail-2col')

@section('left-column')

<div id="toolbox">
	<h1>{{ FA::icon('wrench') }}&nbsp;&nbsp;Toolbox</h1>
	<h3>Classes:</h3>
	<div class="panel-group" id="accordion">

		{{-- Begin accordion section --}}
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4>
					<a data-toggle="collapse" data-parent="#accordion" href="#collapse-one">
						<span class="accordion-closed">
							{{FA::icon('chevron-right')}}&nbsp;
						</span>
						<span class="accordion-open">
							{{FA::icon('chevron-down')}}
						</span>
						&nbsp;<b>Once Per Week</b>
					</a>
				</h4>
			</div>
			<div id="collapse-one" class="panel-collapse collapse">
				<div class="panel-body">
					Class options...
				</div>
			</div>
		</div>
		{{-- End accordion section --}}

		{{-- Begin accordion section --}}
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4>
					<a data-toggle="collapse" data-parent="#accordion" href="#collapse-two">
						<span class="accordion-closed">
							{{FA::icon('chevron-right')}}&nbsp;
						</span>
						<span class="accordion-open">
							{{FA::icon('chevron-down')}}
						</span>
						&nbsp;<b>2&times; Per Week</b> <em>(M, W or T, Th)</em>
					</a>
				</h4>
			</div>
			<div id="collapse-two" class="panel-collapse collapse">
				<div class="panel-body">
					Class options...
				</div>
			</div>
		</div>
		{{-- End accordion section --}}
		
		{{-- Begin accordion section --}}
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4>
					<a data-toggle="collapse" data-parent="#accordion" href="#collapse-three">
						<span class="accordion-closed">
							{{FA::icon('chevron-right')}}&nbsp;
						</span>
						<span class="accordion-open">
							{{FA::icon('chevron-down')}}
						</span>
						&nbsp;<b>3&times; Per Week</b> <em>(M, W, F)</em>
					</a>
				</h4>
			</div>
			<div id="collapse-three" class="panel-collapse collapse">
				<div class="panel-body">
					Class options...
				</div>
			</div>
		</div>
		{{-- End accordion section --}}

		{{-- Begin accordion section --}}
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4>
					<a data-toggle="collapse" data-parent="#accordion" href="#collapse-four">
						<span class="accordion-closed">
							{{FA::icon('chevron-right')}}&nbsp;
						</span>
						<span class="accordion-open">
							{{FA::icon('chevron-down')}}
						</span>
						&nbsp;<b>5&times; Per Week</b>
					</a>
				</h4>
			</div>
			<div id="collapse-four" class="panel-collapse collapse">
				<div class="panel-body">
					Class options...
				</div>
			</div>
		</div>
		{{-- End accordion section --}}

		{{-- Begin accordion section --}}
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4>
					<a data-toggle="collapse" data-parent="#accordion" href="#collapse-five">
						<span class="accordion-closed">
							{{FA::icon('chevron-right')}}&nbsp;
						</span>
						<span class="accordion-open">
							{{FA::icon('chevron-down')}}
						</span>
						&nbsp;<b>Custom Time Block</b>
					</a>
				</h4>
			</div>
			<div id="collapse-five" class="panel-collapse collapse">
				<div class="panel-body">
					<div id="custom-block-form-container">
						<h4>DAYS:</h4>
						<div id="custom-days">
							<div class="day-checkbox"><input type="checkbox"> M</input></div>
							<div class="day-checkbox"><input type="checkbox"> T</input></div>
							<div class="day-checkbox"><input type="checkbox"> W</input></div>
							<div class="day-checkbox"><input type="checkbox"> Th</input></div>
							<div class="day-checkbox"><input type="checkbox"> F</input></div>
						</div>
						<h4>DURATION:</h4>
						<div id="custom-duration" class="input-group spinner">
							<input id="custom-duration-input" type="text" class="form-control" value="50" disabled>
							<div class="input-group-btn-vertical">
								<button id="add-time-btn" class="btn btn-default">
									{{ FA::icon('caret-up') }}
								</button>
								<button id="remove-time-btn" class="btn btn-default">
									{{ FA::icon('caret-down') }}
								</button>
							</div>
							<div id="minutes-label">minutes</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		{{-- End accordion section --}}
		
	</div>
</div>

@stop

@section('center-column')

<h1>{{ $schedule->name }}</h1>

<div id="sched-col-headers">
	<div class="sched-col-header">
		<h3>MONDAY</h3>
	</div>
	<div class="sched-col-header">
		<h3>TUESDAY</h3>
	</div>
	<div class="sched-col-header">
		<h3>WEDNESDAY</h3>
	</div>
	<div class="sched-col-header">
		<h3>THURSDAY</h3>
	</div>
	<div class="sched-col-header">
		<h3>FRIDAY</h3>
	</div>
</div>
<div id="time-labels">
	<p>8:00</p>
	<p>9:00</p>
	<p>10:00</p>
	<p>11:00</p>
	<p>12:00</p>
	<p>1:00</p>
	<p>2:00</p>
	<p>3:00</p>
	<p>4:00</p>
	<p>5:00</p>
	<p>6:00</p>
	<p>7:00</p>
	<p>8:00</P>
</div>
<div id="sched-container">
	<div class="sched-day-column" id="mon-col">
	</div>
	<div class="sched-day-column" id="tue-col">
	</div>
	<div class="sched-day-column" id="wed-col">
	</div>
	<div class="sched-day-column" id="thu-col">
	</div>
	<div class="sched-day-column" id="fri-col">
	</div>
</div>

@stop