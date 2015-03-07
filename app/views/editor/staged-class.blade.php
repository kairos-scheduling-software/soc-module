<?php
	$etime = $class->etime;
	$day_string = " " . $etime->days;
	$days = array();

	// Parse the days this class is held
	if (strpos($day_string, "1"))
		$days[] = "#mon-col";
	if (strpos($day_string, "2"))
		$days[] = "#tue-col";
	if (strpos($day_string, "3"))
		$days[] = "#wed-col";
	if (strpos($day_string, "4"))
		$days[] = "#thu-col";
	if (strpos($day_string, "5"))
		$days[] = "#fri-col";

	// For now, blocks can only be either 50 or 80 minutes
	$block_class = $etime->length == 80 ? "eighty-min-blk" : "fifty-min-blk";
?>

@foreach($days as $day)
	<div class="{{ $block_class }} scheduled-class" data-col="{{ $day }}" data-days="{{$etime->days}}"
		 data-group="{{ $group_count . '-' . $etime->id }}" data-start="{{ $etime->starttm }}" data-length="{{ $etime->length }}">
		{{ $class->name }}
	</div>
@endforeach