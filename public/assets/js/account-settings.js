$(function() {

	$('#send_mail').change(function(e) {
		$('#updating-email').hide();
		$('#updated-email').hide();

		$.ajax({
			url:		toggle_emails_url,
			type: 		"POST",
			beforeSend:	function() {
				$('#updating-email').css('display', 'inline');
			},
			success: 	function(data, textStatus, jqXHR) {
				setTimeout(function() {
					$('#updating-email').hide();
					$('#updated-email').css('display', 'inline');
				}, 700);

				setTimeout(function() {
					$('#updated-email').fadeOut(600);
				}, 2500);

				console.log("successfully updated email settings");
			},
			error: 		function(jqXHR, textStatus, errorThrown) {
				$('#updating-email').hide();
				alert('Could not update email notification settings.');
			}
		});
	});

	$('#update-email-form').submit(function(e) {
		e.preventDefault();

		var form = $(this);
		var data = form.serializeArray();
		var url = form.attr('action');

		$.ajax({
			url:		url,
			type: 		"POST",
			data: 		data,
			beforeSend:	function() {
				$('#updating-email').css('display', 'inline');
			},
			success: 	function(data, textStatus, jqXHR) {
				setTimeout(function() {
					$('#updating-email').hide();
					$('#updated-email').css('display', 'inline');
				}, 700);

				setTimeout(function() {
					$('#updated-email').fadeOut(600);
				}, 2500);

				console.log("successfully updated email address");
			},
			error: 		function(jqXHR, textStatus, errorThrown) {
				$('#updating-email').hide();
				alert('Could not update email address.');
			}
		});
	});

	$('#update-pw-form').submit(function(e) {
		e.preventDefault();

		var form = $(this);
		var data = form.serializeArray();
		var url = form.attr('action');

		$.ajax({
			url:		url,
			type: 		"POST",
			data: 		data,
			beforeSend:	function() {
				$('#updating-pw').css('display', 'inline');
			},
			success: 	function(data, textStatus, jqXHR) {
				setTimeout(function() {
					$('#updating-pw').hide();
					$('#updated-pw').css('display', 'inline');
				}, 700);

				setTimeout(function() {
					$('#updated-pw').fadeOut(600);
				}, 2500);

				console.log("successfully updated password");
			},
			error: 		function(jqXHR, textStatus, errorThrown) {
				$('#updating-pw').hide();
				alert('Could not update password');
			}
		});
	});

	$('#change-avatar-btn').click(function(e) {
		e.preventDefault();
		$('#fileInput').click();
	});

	$('#fileInput').change(function() {
		var form = $('#avatar-form');
		var url = form.attr('action');
		var data = new FormData(form[0]);

		$.ajax({
			processData: false,
    		contentType: false,
			url:		url,
			type: 		"POST",
			data: 		data,
			beforeSend: function() {
				$('#update-image-container').show();
				$('#updating-pic').css('display', 'inline');
			}, 
			success: 	function(data, textStatus, jqXHR) {
				setTimeout(function() {
					$('#updating-pic').hide();
					$('#updated-pic').css('display', 'inline');
					$('#avatar').attr('src', data);
				}, 700);

				setTimeout(function() {
					$('#updated-pic').fadeOut(600);
					$('#update-image-container').fadeOut(600);
				}, 2500);

				console.log("successfully updated profile pic");
			},
			error: 		function(jqXHR, textStatus, errorThrown) {
				$('#updating-pic').hide();
				$('#update-image-container').hide();
				alert('Could not update profile pic');	
			}
		});
	});

});