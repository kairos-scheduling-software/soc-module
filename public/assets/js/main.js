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
				$('.data-list').append(data);
			},
			error: 		function(jqXHR, textStatus, errorThrown) {
				var message = $.parseJSON(jqXHR.responseText);
				alert(message.error);
				// TODO:  bootstrap error message
			}
		});
	});
});