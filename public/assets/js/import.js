$(function() 
{
	$("#import-mode").change(function() {
		var mode = $('#import-mode option:selected').val();
		$('[id=clear]').addClass('hide');
		$('#global-error').addClass('hide');
		place_description(mode);
		update_header(mode);
		shownScheduleSelector(mode);

		$("#import").attr("action", $('#import-mode option:selected').attr('data-url'));
	});

	update_type();
});

function update_type()
{
	var mode = $('#import-mode option:selected').val();
	place_description(mode);
	update_header(mode);
	shownScheduleSelector(mode);
	$("#import").attr("action", $('#import-mode option:selected').attr('data-url'));
}

function place_description(mode)
{
	var description = "";
	if(mode == 'Import Constraint')
	{
		description = "Select a CSV file that has constraints for classes within a already made schedule. It will be "
					+ "expecting the columns in the following order"
					+ "<br><br>"
					+ "Class - 2 Columns of classes that will be used to form a relationship. <br>"
					+ "Note: The class names must be exactly as they are shown on the schedule "
					+ "<br><br>"
					+ "Key - The type of relationship they have out of the following"
					+ "<hr>"
					+ "after - the class in the first column must be after the second column class <br><br>"
					+ "before - the class in the first column must be before the second column class <br><br>"
					+ "equal - the classes metioned will be at the same time <br><br>"
					+ "not - the classes metioned cannot be at the same time <br><br>";
	}
	else if(mode == 'Import Professors')
	{
		description = "Select a CSV file that contains a list of professors. It will be expecting the columns in the following order: "
					+ "</br></br>"
					+ "UUID - The university id of the professor teaching the class"
					+ "</br></br>"
					+ "Professor- The name of the professor"
					+ "</br></br>";
	}
	else if(mode == 'Import Rooms')
	{
		description = "Select a CSV file that containts a list of rooms. It will be expecting the columns in the following order: "
					+ "</br></br>"
					+ "Room - The name of the room that will be used"
					+ "</br></br>"
					+ "Capacity - How many people can fit in the room"
					+ "</br></br>";
	}
	else
	{
		description = "Select a CSV file that mirrors a full schedule. It will be expecting the columns in the following order: "
					+ "</br></br>"
					+ "Room - The name of the room that will be used"
					+ "</br></br>"
					+ "Capacity - How many people can fit in the room"
					+ "</br></br>"
					+ "UUID - The university id of the professor teaching the class"
					+ "</br></br>"
					+ "Professor- The name of the professor"
					+ "</br></br>"
					+ "Class - The name of the class I.E. CS1410-01"
					+ "</br></br>"
					+ "Title - The title of the class I.E. Object Oriented Programming"
					+ "</br></br>"
					+ "Type - The type of class it is from the following Laboratory, Discussion, Lecture, Seminar, or Special Topics"
					+ "</br></br>"
					+ "Time - The time of the class in the format day then time such as M1300|W1300"
					+ "</br></br>"
					+ "Length - The length of the class in minutes I.E. 80"
					+ "</br></br>";
	}

	$('#import-description').html(description);
}

function update_header(mode)
{	
	var title = "Import Full Schedule";

	if(mode == 'Import Constraint')
	{
		title = "Import Schedule Constraints";
	}
	else if(mode == 'Import Professors')
	{
		title = "Import Professors";
	}
	else if(mode == 'Import Rooms')
	{
		title = "Import Rooms";
	}

	$("#Import-Selected").html(title);
	$("#Import-Selected").val(title);
}

function shownScheduleSelector(mode)
{
	if(mode == 'Import Constraint')
	{
		$('#ImportFullDiv').addClass("hide");
		$('#ImportWithDropDown').removeClass("hide");
		$('#ImportConstraintDiv').removeClass("hide");
	}
	else if(mode == 'Import Professors' || mode == 'Import Rooms')
	{
		$('#ImportFullDiv').addClass("hide");
		$('#ImportWithDropDown').removeClass("hide");
		$('#ImportConstraintDiv').addClass("hide");
	}
	else
	{
		$('#ImportFullDiv').removeClass("hide");
		$('#ImportWithDropDown').addClass("hide");
		$('#ImportConstraintDiv').addClass("hide");
	}
}