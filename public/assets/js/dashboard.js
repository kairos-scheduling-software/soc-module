$(function(){
	$('.sched-list-row').click(function(e) {
		e.preventDefault();
		$('#hg-right-content').html("<h1>Loading...</h1>");
		var url = $(this).attr('data-url');

		$.ajax({
			url:		url,
			type: 		"POST",
			success: 	function(data, textStatus, jqXHR) {
				$('#hg-right-content').html(data);
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

	$('#hg-right-content').on('click', '#view-sched-btn', function(e) {
		var url = $(this).attr('data-url');
		window.location.href = url;
	});
});