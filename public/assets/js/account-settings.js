$(function() {

	$('#send_mail').change(function(e) {
		//alert(this.checked);
		$.ajax({
			url:		toggle_emails_url,
			type: 		"POST",
			success: 	function(data, textStatus, jqXHR) {
				console.log("successfully updated email settings");
			},
			error: 		function(jqXHR, textStatus, errorThrown) {
				alert('Could not update email notification settings.');
			}
		});
	});
});