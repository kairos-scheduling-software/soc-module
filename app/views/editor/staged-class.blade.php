<?php
	$etime = $class->etime;
	$day_string = " " . $etime->days;
	$days = array();
	$rm_grp = $class->room_group_id;
	$rm = $class->room_id;
	if ($rm_grp == null) $rm_grp = -1;
	if ($rm == null) $rm = -1;

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
	$drag_class = "";

	switch(count($days))
	{
		case 1:
			$drag_class .= "one";
			break;
		case 2:
			$drag_class .= "two";
			break;
		case 3:
			$drag_class .= "three";
			break;
		case 4:
			$drag_class .= "four";
			break;
		case 5:
			$drag_class .= "five";
			break;
	}

	$drag_class .= $etime->length == 80 ? "-eighty" : "-fifty";
?>

@if ($etime->length == 80 || $etime->length == 50)
	@foreach($days as $day)
		<?php
			$ddd;

			if (strpos($day, "mon"))
				$ddd = "mon";
			if (strpos($day, "tue"))
				$ddd = "tue";
			if (strpos($day, "wed"))
				$ddd = "wed";
			if (strpos($day, "thu"))
				$ddd = "thu";
			if (strpos($day, "fri"))
				$ddd = "fri";
		?>
		<div class="{{ $block_class }} scheduled-class {{$drag_class}}" data-id="{{ $class->id }}" data-ddd="{{ $ddd }}"
			data-days="{{$etime->days}}" data-time="{{ $etime->id }}" data-start="{{ $etime->starttm }}" data-length="{{ $etime->length }}"
			data-room_id="{{ $rm }}" data-grp_id="{{ $rm_grp }}"
			data-enroll="{{ $class->enroll_cap }}" data-prof_id="{{ $class->professor }}">
			{{ $class->name }}
		</div>
	@endforeach
@endif
