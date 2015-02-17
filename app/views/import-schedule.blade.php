@extends('layouts.main')

@section('content')

<div class="top-buffer"></div>
<div class="container">
	<h1>Import Schedule</h1>
	<div class="col-md-8 row">
		<form method="post" id="import" action="{{ URL::route('import-post') }}" enctype="multipart/form-data">
		<h3>
			Schedule Name
			<div><input type = "text" id = "ScheduleName" name ="ScheduleName" value="{{Input::old('ScheduleName')}}"/>
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
	
</div>

@stop