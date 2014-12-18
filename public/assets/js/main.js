$(function(){
	$('.event-view-link').click(function(e){
		e.preventDefault();
		var name = $(this).attr('data-name');
		//highlightBlock(name);
		//alert(name);
		return false;
	});

	$('.view-class-list-row').click(function() {
		$(this).find('a').click();
		return false;
	});

	$('#add-class-form').submit(function(e) {
		e.preventDefault();
		var form = $(this);
		var postData = form.serializeArray();
		var url = form.attr("action");

		$.ajax({
			url:		url,
			type: 		"POST",
			data: 		postData,
			success: 	function(data, textStatus, jqXHR) {
				class_data_count++;
				$('#empty-data-list').hide();
				$('#classes-data-list').append("<div class='class-listing-container'>" + data + "</div>");
				$('#classes-data-list').show();
			},
			error: 		function(jqXHR, textStatus, errorThrown) {
				var message = $.parseJSON(jqXHR.responseText);
				alert(message.error);
				// TODO:  bootstrap error message
			}
		});
	});

	$('.edit-class-form').submit(function(e) {
		e.preventDefault();
		var form = $(this);
		var postData = form.serializeArray();
		var url = form.attr('action');
		var container = form.closest('div.class-listing-container');

		$.ajax({
			url:		url,
			type: 		"POST",
			data: 		postData,
			success: 	function(data, textStatus, jqXHR) {
				container.html(data);
			},
			error: 		function(jqXHR, textStatus, errorThrown) {
				var message = $.parseJSON(jqXHR.responseText);
				alert(message.error);
				// TODO:  bootstrap error message
			}
		});
	});

	$('#classes-data-list').on('click', '.edit-class', function(e) {
		var edit_row = $(this).attr('data-edit');
		var selector = '#class-listing-' + $(this).attr('data-id');
		$(selector).hide();
		$(edit_row).show();
	});

	$('#classes-data-list').on('click', '.btn-cancel', function(e) {
		// Don't submit the form
		e.preventDefault();

		// Hide the edit form
		var button = $(this);
		button.closest('div.edit-class-row').hide();

		// Reset the input field to the original name
		var input = button.attr('data-input');
		var name = button.attr('data-name');
		$(input).val(name);

		// Show the data row
		var row = button.attr('data-row');
		$(row).show();
	});

	$('#classes-data-list').on('click', '.remove-class', function(e) {
		if (!confirm("Are you sure you want to delete this class?"))
			return;

		var url = $(this).attr('data-url');
		var container = $(this).closest('div.class-listing-container');

		$.ajax({
			url:		url,
			type: 		"POST",
			success: 	function(data, textStatus, jqXHR) {
				class_data_count--;
				if(class_data_count == 0)
				{
					$('#empty-data-list').show();
					$('#classes-data-list').hide();
				}
				container.remove();
			},
			error: 		function(jqXHR, textStatus, errorThrown) {
				var message = $.parseJSON(jqXHR.responseText);
				alert(message.error);
				// TODO:  bootstrap error message
			}
		});
	});
});