@extends('layouts.holy-grail-2col')

@section('left-column')
<h2 id="import-selected">Schedule Admin</h2>
	
<select id="import-mode">
	<option selected>
		Import Schedule
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
	Time - The time of the class in the format day then time such as M1300|W1300
	</br></br>
	Length - The length of the class I.E. 80
	</br></br>
</p>
@stop

@section('center-column')
<h1>Import Schedule</h1>
	<form method="post" id="import" action="{{ URL::route('import-post') }}" enctype="multipart/form-data">
	<h3>
		Schedule Name
		<div><input type="text" id="ScheduleName-text" name ="ScheduleName-text" value="{{Input::old('ScheduleName')}}"/>
			<span>
				@if($errors->has('scheduleName'))
					{{$errors->first('scheduleName')}}
				@endif
			</span>
		</div>
	</h3>

	<h3>
	Import File
		<div><input type="file" id="import" name="import">
			<span>
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
</div>

@stop