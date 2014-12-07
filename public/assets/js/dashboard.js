$(function(){
	$('.sched-list-row').click(function(e) {
		e.preventDefault();
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
});