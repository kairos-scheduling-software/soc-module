@extends('layouts.main')

@section('content')

<div class="container">
	<div class="top-buffer"></div>
	<div class="data-entry-header">
		<h1>Data Entry</h1>
		<img src="{{ URL::asset('assets/images/prog_01.png') }}"/>
	</div>
	<span id="prog-label-1">Classes</span>
	<div class="data-entry-body">
		<div class="data-list">
			@for($i = 0; $i < 30; $i++)
				@include('blocks/class-listing')
			@endfor
		</div>		
		<form>
			<input type="text" class="form-control class-name-input" placeholder="Class Name..."/>
			<button type="submit" class="btn btn-md btn-default add-class-btn">
				Add {{ FA::lg('plus')}}
			</button>
		</form>
	</div>
</div>

@stop