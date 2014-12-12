@extends('layouts.main')

@section('content')

<div class="container">
	<div class="top-buffer"></div>
	<div class="data-entry-header">
		<h1>Data Entry: {{ $schedule->name }}</h1>
		@yield('prog-bar')
	</div>
	<div id="prog-label-container">
		<table id="progress-labels">
			<tr>
				@yield('prog-bar-labels')
			</tr>
		</table>
	</div>
	<div class="data-entry-body2">
		@yield('data-entry-body')
	</div>
</div>

@stop