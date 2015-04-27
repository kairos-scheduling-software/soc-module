@extends('layouts.2col-fixed')

@section('left-column')
<script src="{{ URL::asset('assets/js/jquery.panelslider.min.js') }}"></script>
<style>
	.drag-cursor {
		cursor: url('{{ URL::asset("assets/images/drag-cursor-sm.png") }}'), auto;
	}
</style>
<div id="toolbox">
	<h1>{{ FA::icon('wrench') }}&nbsp;&nbsp;Toolbox</h1>
	<h3><span class="plus-icon">{{ FA::icon('plus') }}</span> Add Classes:</h3>
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
					<table class="toolbox-blocks">
						<tr>
							<td>50 min:</td>
							<td>80 min:</td>
						</tr>
						<tr>
							<td><div class="fifty-min-blk draggable one-fifty"></div></td>
							<td><div class="eighty-min-blk draggable one-eighty"></div></td>
						</tr>
					</table>
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
					<table class="toolbox-blocks">
						<tr>
							<td>50 min:</td>
							<td>80 min:</td>
						</tr>
						<tr>
							<td><div class="fifty-min-blk draggable two-fifty"></div></td>
							<td><div class="eighty-min-blk draggable two-eighty"></div></td>
						</tr>
					</table>
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
					<table class="toolbox-blocks">
						<tr>
							<td>50 min:</td>
						</tr>
						<tr>
							<td><div class="fifty-min-blk draggable three-fifty"></div></td>
						</tr>
					</table>
				</div>
			</div>
		</div>
		{{-- End accordion section --}}

		{{-- Begin accordion section 
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
		 End accordion section --}}

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
						<table id="custom-times">
							<tr>
								<td>
									<h4>Start Time:</h4>
								</td>
								<td>
									<h4>End Time:</h4>
								</td>
							</tr>
							<tr>
								<td>
									<div class="bootstrap-timepicker input-group">
										<input id="cust-start-time" type="text" class="form-control">
										<span id="start-time-clock" class="input-group-addon">{{ FA::icon('clock-o') }}</span>
									</div>
								</td>
								<td>
									<div class="bootstrap-timepicker input-group">
										<input id="cust-end-time" type="text" class="form-control">
										<span id="end-time-clock" class="input-group-addon">{{ FA::icon('clock-o') }}</span>
									</div>
								</td>
							</tr>
						</table>{{--
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
						</div>--}}
					</div>
				</div>
			</div>
		</div>
		{{-- End accordion section --}}

		<h3><span class="view-icon">{{ FA::flipHorizontal('search') }}</span> On the Schedule:</h3>
		{{-- Begin accordion section --}}
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4>
					<a data-toggle="collapse" data-parent="#accordion" href="#collapse-six">
						<span class="accordion-closed">
							{{FA::icon('chevron-right')}}&nbsp;
						</span>
						<span class="accordion-open">
							{{FA::icon('chevron-down')}}
						</span>
						&nbsp;<b>Scheduled Classes</b>
					</a>
				</h4>
			</div>
			<div id="collapse-six" class="panel-collapse collapse">
				<div class="panel-body">
					<div class="ui-widget" id="class-search-container">
						<p>Type to Search the Schedule:</p>
						<input id="class-search" class="form-control" placeholder="Class Name"/>
						<span id="search-clear">{{FA::icon('times-circle')}}</span>
					</div>
				</div>
			</div>
		</div>
		{{-- End accordion section --}}

		<div id="conflict-section">
			<h3><span class="warning-icon">{{ FA::icon('warning') }}</span> Schedule Conflicts:</h3>
			{{-- Begin accordion section --}}
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4>
						<a data-toggle="collapse" data-parent="#accordion" href="#collapse-seven">
							<span class="accordion-closed">
								{{FA::icon('chevron-right')}}&nbsp;
							</span>
							<span class="accordion-open">
								{{FA::icon('chevron-down')}}
							</span>
							&nbsp;<b>View Conflicts</b>
						</a>
					</h4>
				</div>
				<div id="conflict-list" class="panel-collapse collapse">
					<div class="panel-body">
						<b>CS 9000</b> conflicts with <b>CS 1400-008</b>
					</div>
				</div>
			</div>
			{{-- End accordion section --}}
		</div>

	</div><!-- end div id=accordion -->
</div>

@stop

@section('main-column')

<div id="toggle-container">
	<a id="toggle-toolbox" href="#left-side-bar">{{ FA::icon('chevron-right') }}</a>
</div>

<script>
	var panel_is_open = false;
	$('#toggle-toolbox').click(function(e) {
		e.preventDefault();
		
		setTimeout(function() {
			$('#left-side-bar').zIndex(10);
			$('#page_footer').zIndex(11);
			$('#toggle-container').zIndex(11);
		}, 20);

		if (panel_is_open)
		{
			$('#toggle-container').animate({marginLeft: 0}, {duration: 200}); 
			$.panelslider.close(function() { $('#toggle-toolbox').html('<i class="fa fa-chevron-right"></i>'); });
			panel_is_open = false;
		}
		else
		{
			//$('#toggle-container').zIndex($('#left-side-bar').zIndex() - 1);
			$('#toggle-container').animate({marginLeft: +350}, {duration: 200});
			panel_is_open = true;

			if(right_panel_open)
				$('#close-right-panel').click();
		}
		
		return false;
	});
	$('#toggle-toolbox').panelslider({
		onOpen: function() {
			var z = Math.min($('#custom_navbar').zIndex(), $('page_footer').zIndex());
			$('#left-side-bar').zIndex(10);
			//$('#left-side-bar').css('position', 'absolute');
			//$('#main-column').zIndex(z-2);
			$('#page_footer').zIndex(11);
			$('#toggle-container').zIndex(11);
			$('#toggle-toolbox').html('<i class="fa fa-chevron-left"></i>');
		},
		clickClose: false
	});

</script>

<h1 id="sched-name" data-url="{{ URL::route('get-sched') }}">{{ $schedule->name }}
	{{--<a href="#"><span id="edit-icon">{{FA::icon('edit')}}</span></a>--}}
		<span id="sched-ok">{{ FA::icon('check')}} No conflicts in schedule.</span>
		<span id="checking-sched">{{ FA::spin('spinner') }} Checking schedule...</span>
		<span id="sched-bad">{{ FA::icon('warning') }} 
			<a id="view-conflicts" href="#">Conflicts in schedule!  Click to view.</a>
		</span>

</h1>

<div id="outer-container">
	<div id="sched-col-headers">
		<div class="sched-col-header" id="empty-cell"></div>
		<div class="sched-col-header" id="mon-col-header">
			<h3>MONDAY</h3>
		</div>
		<div class="sched-col-header" id="tue-col-header">
			<h3>TUESDAY</h3>
		</div>
		<div class="sched-col-header" id="wed-col-header">
			<h3>WEDNESDAY</h3>
		</div>
		<div class="sched-col-header" id="thu-col-header">
			<h3>THURSDAY</h3>
		</div>
		<div class="sched-col-header" id="fri-col-header">
			<h3>FRIDAY</h3>
		</div>
	</div>
	<div id="inner-container">
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
		{{--<div id="sched-container">--}}
			<div class="sched-day-column" id="mon-col" data-ddd="mon">
			</div>
			<div class="sched-day-column" id="tue-col" data-ddd="tue">
			</div>
			<div class="sched-day-column" id="wed-col" data-ddd="wed">
			</div>
			<div class="sched-day-column" id="thu-col" data-ddd="thu">
			</div>
			<div class="sched-day-column" id="fri-col" data-ddd="fri">
			</div>
		{{--</div>--}}
	</div>
</div>
<div id="bottom-container">
	<div id="drop-trash">
		<img id="trash-img" src="https://cdn3.iconfinder.com/data/icons/flatforlinux/256/24-Empty%20Trash.png" width="100" height="100" class="trash-can">
	</div>
</div>
<div id="hidden-data" class="hide"
	 data-schedid="{{ $schedule->id }}"
	 data-cursor="{{ URL::asset('assets/images/drag-cursor-sm.png') }}">
</div>

@stop

@section('right-column')

<div id="edit-class-panel" data-url="{{ URL::route('e-edit-schedule') }}">
	<div style="text-align: right">
		<h1 style="margin-bottom: 0">
			<a href="#" id="close-right-panel">&times;</a>
		</h1>
	</div>
	<div>
		<h3 style="margin-top: 0">Class Info</h3>
		<form id="edit-panel-form" action="">
			<input type="hidden" class="form-control" name="class_id" value=""/>
			<div class="form-group">
				<label for="edit-panel-class-name">Class Name:</label>
				<input type="text" class="form-control" name="class_name" id="edit-panel-class-name" required>
			</div>

			<div class="form-group">
				<label for="edit-panel-prof-select">Professor:</label><br>
				<select class="form-control" name="prof">
				</select>
			</div>

			<div class="form-group">
				<label for="edit-panel-roomgroup-select">Max Capacity:</label><br>
				<input type="text" class="form-control" name="enroll">
			</div>

			<div class="form-group">
				<label for="edit-panel-roomgroup-select">Room Group:</label><br>
				<select class="form-control" name="room_group" data-url="{{ URL::route('room-group-list') }}">
				</select>
			</div>

			<div class="form-group">
				<label for="edit-panel-room-select">Room:</label><br>
				<select class="form-control" name="room" data-url="{{ URL::route('room-list') }}">
				</select>
			</div>

			<hr>
			<h3>Constraints 
				<button class="btn btn-sm" id="add-const-btn" title="Add a constraint">{{ FA::icon('plus') }}</button>
			</h3>

			<div class="form-group">
				<table id="constraint-table">
					<tr><th>Key:</th><th colspan="2">Value:</th></tr>
					<tr class="constraint-row">
						<td>
							<select class="form-control" name="constraint-key"><option>Avoid Overlap With</option></select>
						</td>
						<td>
							<select class="form-control" name="constraint-val"><option>CS 4540-001</option></select>
						</td>
						<td class="constraint-del">{{ FA::icon('trash') }}</td>
					</tr>
				</table>
			</div>
			<div class="form-group">
				<button class="btn btn-primary">{{ FA::icon('save') }} SAVE</button>
				<button class="btn btn-default" id="cancel-edit-panel">{{ FA::icon('times') }} CANCEL</button>
			</div>
		</form>
	</div>
</div>

@stop
