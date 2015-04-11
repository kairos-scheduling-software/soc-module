@extends('layouts.holy-grail-2col')

@section('left-column')
<select id="import-mode" name="import-mode">
	<option data-url="{{URL::route('import-post')}}" value="Import Full" 
		@if($selected == "Import Full")
			selected
		@endif>
		Import Full Schedule
	</option>
	<option data-url="{{URL::route('import-resources', 'constraint')}}" value="Import Constraint" 
		@if($selected == "Import Constraint")
			selected
		@endif>
		Import Constraints
	</option>
	<option data-url="{{URL::route('import-resources', 'professor')}}" value="Import Professors" 
		@if($selected == "Import Professors")
			selected
		@endif>
		Import Professors
	</option>
	<option data-url="{{URL::route('import-resources', 'room')}}" value="Import Rooms" 
		@if($selected == "Import Rooms")
			selected
		@endif>
		Import Rooms
	</option>
</select>
<hr>
<div class="import-description-div">
	<p id="import-description">
		Select a CSV file that mirrors a full schedule. It will be expecting the columns in the following order: 
		</br></br>
		Room - The name of the room that will be used
		</br></br>
		Capacity - How many people can fit in the room
		</br></br>
		UUID - The university id of the professor teaching the class
		</br></br>
		Professor- The name of the professor
		</br></br>
		Class - The name of the class I.E. CS1410-01
		</br></br>
		Title - The title of the class I.E. Object Oriented Programming
		</br></br>
		Type - The type of class it is from the following Laboratory, Discussion, Lecture, Seminar, or Special Topics
		</br></br>
		Time - The time of the class in the format day then time such as M1300|W1300
		</br></br>
		Length - The length of the class in minutes I.E. 80
		</br></br>
	</p>
</div>

@stop

@section('center-column')
<form method="post" id="import" action="{{ URL::route('import-post') }}" enctype="multipart/form-data">
	<h1 id="Import-Selected">Import Full Schedule</h1>

	<h3>
		Schedule Name
		<div id='ImportFullDiv'>
			<div><input class="input-import" type="text" id="ScheduleName-text" name ="ScheduleName-text" value="{{Input::old('ScheduleName-text')}}"/>
				<span id="clear">
					@if($errors->has('scheduleName'))
						{{$errors->first('scheduleName')}}
					@endif
				</span>
			</div>
			Semester
			</br>
			<select class="input-import-dropdown" id='semester' name='semester'>
				<option value="Fall" selected> Fall </option>
				<option value="Spring"> Spring </option>
				<option value="Summer"> Summer </option>
			</select></br>
			Year
			</br>
			<select class="input-import-dropdown" id='year' name='year'>
			@foreach($years as $year)
				<option value="{{ $year }}" 
					@if($year == $currentYear)
						selected
					@endif>
					{{ $year }} 
				</option>
			@endforeach
			</select> 
		</div>

		<div id='ImportWithDropDown'>
			<select class="input-import-dropdown" id='schedules' name='schedules'>
			@foreach($schedules as $schedule)
				<option value="{{ htmlspecialchars($schedule->name) }}"
					@if(Input::old('schedules') == $schedule->name)
						selected
					@endif>
					{{ htmlspecialchars($schedule->name) }}
				</option>
			@endforeach
			</select>
			<span>
				@if($errors->has('select schedule'))
					{{$errors->first('select schedule')}}
				@endif
			</span>
			</br>
			<div id='ImportConstraintDiv' name='ImportConstraintDiv'>
				<input type="checkbox" name="Replace" value="True">&nbsp Remove previous constraints for schedule<br>
			</div>
		</div>
	</h3>

	<h3>
	Import File
		<div><input type="file" id="import" name="import">
			<span id="clear">
				@if($errors->has('uploadfile'))
					{{$errors->first('uploadfile')}}
				@endif
			</span>
		</div></br>

		<button class="btn new-sched" id="import-schedule-btn">
			{{ FA::icon('cloud-upload') }}&nbsp;&nbsp;
				Import
		</button>
	</h3>
	</form>

	<div id="global-error">
		@if(isset($global))
			{{$global}}
		@endif
	</div>
</div>

@stop