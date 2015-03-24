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
		<div class="{{ $block_class }} scheduled-class {{$drag_class}}" data-class="{{ $class->id }}" data-col="{{ $day }}" data-ddd="{{ $ddd }}"
			data-days="{{$etime->days}}" data-group="{{ $group_count . '-' . $etime->id }}" data-start="{{ $etime->starttm }}" data-length="{{ $etime->length }}">
			<span class="class-name-container">{{ $class->name }}</span>
		</div>
	@endforeach
@endif