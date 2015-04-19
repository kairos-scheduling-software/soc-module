var primary = "header_year_col";

$(function(){

	var modal_top = $(window).height() / 2;
	modal_top -= $('#create-sched-modal').height() / 2;
	var modal_left = $(window).width() / 2;
	modal_left -= $('#create-sched-modal').width() / 2;

	$('#create-sched-modal').css('top', modal_top);
	$('#create-sched-modal').css('left', modal_left);

	$('#create-sched-btn').click(function(e) {
		$('#create-sched-form').attr("action", $(this).attr('data-url'));
		$("#create-sched-modal").modal('show');
	});

	$('#header_name_col').click(function()
	{
		$('#' + primary).removeAttr('primary');
		primary = "header_name_col";
		$('#' + primary).attr('primary', true);
		toggleUp("header_name_col");
		createScheduleRows();
	});

	$('#header_year_col').click(function()
	{
		$('#' + primary).removeAttr('primary');
		primary = "header_year_col";
		$('#' + primary).attr('primary', true);
		toggleUp("header_year_col");
		createScheduleRows();
	});

	$('#header_semester_col').click(function()
	{
		$('#' + primary).removeAttr('primary');
		primary = "header_semester_col";
		$('#' + primary).attr('primary', true);
		toggleUp("header_semester_col");
		createScheduleRows();
	});

	$('#header_edit_col').click(function()
	{
		$('#' + primary).removeAttr('primary');
		primary = "header_edit_col";
		$('#' + primary).attr('primary', true);
		toggleUp("header_edit_col");
		createScheduleRows();
	});

	$('#hg-right-content').on('click', '#copy-sched-btn', function(e) {
		$('#create-sched-form').attr("action", $(this).attr('data-url'));
		$("#create-sched-modal").modal('show');
	});

	$('#hg-right-content').on('click', '#final', function(e) {
    	var checked  = $('#final').is(':checked');
    	var url = $('#final').attr("data-url");
    	if(checked == true)
    		checked = 1;
    	else
    		checked = 0;

    	$.ajax({
			url:		url,
			type: 		"POST",
			data: 		{"data" : checked},
			success: 	function(data, textStatus, jqXHR) {
			},
			error: 		function(jqXHR, textStatus, errorThrown) {
				$('#final').prop('checked', !checked);
				alert("could not update this schedule to the final for the year");
			}
		});

	});

	$('#cancel-create').click(function(e) {
		// Reset the create-schedule form
		$('#create-sched-form').find('input').val("");
		$('#modal-errors').text('');
	});

	$('#create-schedule').click(function(e) {
		$('#create-sched-form').submit();
	});

	$('#create-sched-form').submit(function(e) {
		e.preventDefault();
		var form = $(this);
		var postData = form.serializeArray();
		var url = form.attr("action");
		var name = form.find('input').val();

		$.ajax({
			url:		url,
			type: 		"POST",
			data: 		postData,
			success: 	function(data, textStatus, jqXHR) {
				//console.log(data);
				window.location.href = data;
			},
			error: 		function(jqXHR, textStatus, errorThrown) {
				//alert('Could not add create schedule at this time.');
				// TODO:  bootstrap error message
				if(jqXHR.responseText.indexOf('Name already in use') > -1)
				{
					$('#modal-errors').text('The name "' + name + '" is already in use');
				}
				else
				{
					$('#modal-errors').text('The schedule could not be created at this time');
				}
			}
		});
	});

	$('.sched-list-row').click(function(e) {
		e.preventDefault();
		var url = $(this).attr('data-url');

		$.ajax({
			url:		url,
			type: 		"POST",
			beforeSend: function() {
				$('#loading-admin-panel').show();
				$('#ajax-admin-target').hide();
				$('#hg-right').css('display', 'table-cell');
			}, 
			success: 	function(data, textStatus, jqXHR) {
				$('#loading-admin-panel').hide();
				$('#ajax-admin-target').show();
				$('#ajax-admin-target').html(data);
				$('#hg-right').css('display', 'table-cell');
			},
			error: 		function(jqXHR, textStatus, errorThrown) {
				alert('Could not load schedule at this time.');
				// TODO:  bootstrap error message
			}
		});
	});
	
	$('#hg-right-content').on('click', '#close-btn', function(e) {
		e.preventDefault();
		$('#hg-right').css('display', 'none');
	});

	$('#hg-right-content').on('click', '.sched-action-btn', function(e) {
		var url = $(this).attr('data-url');
		window.location.href = url;
	});

	$('#hg-right-content').on('click', '#delete-sched-btn', function(e) {
		var url = $(this).attr('data-url');
		var row = $(this).attr('data-row');

		if (!confirm("Are you sure you want to delete this schedule?"))
			return;

		$.ajax({
			url:		url,
			type: 		"POST",
			success: 	function(data, textStatus, jqXHR) {
				// TODO:  show bootstrap success message

				$('#hg-right').css('display', 'none');
				$(row).remove();
				alert('Schedule deleted!');
			},
			error: 		function(jqXHR, textStatus, errorThrown) {
				alert('Could not delete schedule at this time.');
				// TODO:  show bootstrap error message
			}
		});
	});
});

function add_description(element)
{
	var tex = $('#' + element).attr('value').trim();

	var html = '<div id="edit-desc-text"><textarea width="100%" style="resize:none;" id="edit-desc-textarea">' + tex + '</textarea>';
	html += '</br><button class="btn btn-small" id="confirm-desc-btn">Confirm</button>&nbsp';
	html += '<button class="btn btn-small" id="cancel-desc-btn">Cancel</button></div>';

    var textarea = $(html);
    $('#' + element).replaceWith(textarea);


    $('#confirm-desc-btn').click(
    	function()
    	{
    		var textFromArea = $('#edit-desc-textarea').val().trim();
    		var url = $('#description-field').attr('data-url');
    		$.ajax({
				url:		url,
				data:  		{"data" : textFromArea}, 
				type: 		"POST",
				success: 	function(data, textStatus, jqXHR) {
					var textdesc = data.schedule.description;

    				if(textdesc == "")
    				{
    					var originalDesc = $('<div id="edit-desc" value="" class="edit-description" onclick="add_description(\'edit-desc\')">Add&nbsp;a&nbsp;description&nbsp;<i class="fa fa-plus fa-lg"></i></div>');
    					textarea.replaceWith(originalDesc);
    				}
    				else
    				{
    					var newDesc = $('<div id="edit-desc" value="' + textFromArea + '" class="edit-description" onclick="add_description(\'edit-desc\')">' + textFromArea + '</div>');
    					textarea.replaceWith(newDesc);
    				}

				$('#row_' + data.schedule.id).find('.sched-list-row').find('.last-edited').html(data.data);
			},
			error: 		function(jqXHR, textStatus, errorThrown) {
				alert('Could not update the description at this time.');
				//TODO: show bootstrap error message
			}
		});
    });

    $('#cancel-desc-btn').click(
    	function()
    	{
    		var html = ""
			if(tex === "")
			{
				html = $('<div id="edit-desc" value="" class="edit-description" onclick="add_description(\'edit-desc\')">Add&nbsp;a&nbsp;description&nbsp;<i class="fa fa-plus fa-lg"></i></div>');
			}
			else
			{
				html = $('<div id="edit-desc" value="' + tex + '" class="edit-description" onclick="add_description(\'edit-desc\')">'+ tex + '</div>');
			}
			$('#edit-desc-text').replaceWith(html);
    });

    textarea.focus();
}

function toggleUp(element)
{
	var changed = $('#' + element).attr('Up');

	if(changed == 'true')
	{
		$('#' + element + " i").replaceWith($('<i class="fa fa-sort-desc"></i>'));
		$('#' + element).attr('Up', 'false');
	}
	else
	{
		$('#' + element + " i").replaceWith($('<i class="fa fa-sort-asc"></i>'));
		$('#' + element).attr('Up', 'true');
	}
}

function createScheduleRows()
{
	var url = $('#sched-list').attr('data-url');
	var up_name = ($('#header_name_col').attr('Up') == 'true') ? 1 : 0;
	var up_year = ($('#header_year_col').attr('Up') == 'true') ? 1 : 0;
	var up_semester = ($('#header_semester_col').attr('Up') == 'true') ? 1 : 0;
	var up_edit = ($('#header_edit_col').attr('Up') == 'true') ? 1 : 0;

	$.ajax({
		url:		url,
		data:  		{"up_name" : up_name, "up_year" : up_year, "up_semester" : up_semester, "up_edit" : up_edit, "primary" : primary}, 
		type: 		"POST",
			success: 	function(data) 
			{
				$('#schedules_list_data').html(data);
				setSchedRow();
			},
			error: 		function(jqXHR, textStatus, errorThrown) 
			{
				alert(jqXHR.responseText);
			}
		});
}

function setSchedRow()
{
	$('.sched-list-row').click(function(e) {
		e.preventDefault();
		var url = $(this).attr('data-url');

		$.ajax({
			url:		url,
			type: 		"POST",
			beforeSend: function() {
				$('#loading-admin-panel').show();
				$('#ajax-admin-target').hide();
				$('#hg-right').css('display', 'table-cell');
			}, 
			success: 	function(data, textStatus, jqXHR) {
				$('#loading-admin-panel').hide();
				$('#ajax-admin-target').show();
				$('#ajax-admin-target').html(data);
				$('#hg-right').css('display', 'table-cell');
			},
			error: 		function(jqXHR, textStatus, errorThrown) {
				alert('Could not load schedule at this time.');
				// TODO:  bootstrap error message
			}
		});
	});
}