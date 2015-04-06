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

	$('#hg-right-content').on('click', '#copy-sched-btn', function(e) {
		$('#create-sched-form').attr("action", $(this).attr('data-url'));
		$("#create-sched-modal").modal('show');
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