@extends('layouts.holy-grail-2col')

@section('left-column')
<select id="import-mode" name="import-mode">
	<option data-url="{{URL::route('import-post')}}" value="Import Full" 
		@if($selected == "Import Full")
			selected
		@endif>
		Import Full Schedule
	</option>
	<option data-url="{{URL::route('import-constraint')}}" value="Import Constraint" 
		@if($selected == "Import Constraint")
			selected
		@endif>
		Import Constraints
	</option>
</select>
<hr>
	
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
@stop

@section('center-column')
<form method="post" id="import" action="{{ URL::route('import-post') }}" enctype="multipart/form-data">
	<h1 id="Import-Selected">Import Full Schedule</h1>

	<h3>
		Schedule Name
		<div><input type="text" id="ScheduleName-text" name ="ScheduleName-text" value="{{Input::old('ScheduleName-text')}}"/>
			<span id="clear">
				@if($errors->has('scheduleName'))
					{{$errors->first('scheduleName')}}
				@endif
			</span>
		</div>

		<select id='schedules' name='schedules'>
		@foreach($schedules as $schedule)
			<option value="{{ htmlspecialchars($schedule->name) }}">
				{{ htmlspecialchars($schedule->name) }} 
			</option>
		@endforeach
		</select> 
		<span>
			@if($errors->has('select schedule'))
				{{$errors->first('select schedule')}}
			@endif
		</span>
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

		<button class="btn btn-primary btn-sm" id="import-schedule-btn">
			Select File
		</button>
	</h3>
	</form>

	<div>
		@if(isset($global))
			{{$global}}
		@endif
	</div>
</div>

@stop