$(function() {
	$('#prof-added').hide();
	
	$('#add-prof-btn').click(function() {
		$('#new-prof-form').fadeIn(100);
		$('#new-prof-name').focus();
	});
	
	$('#new-prof-form').on('submit', function(e) {
		e.preventDefault();
		var form = $(this);
		
		var data = form.serializeArray();
		var url = form.data('url');
		
		$.ajax({
			url: url,
			type: 'post',
			data: data,
			success: function(data, textStatus, jqXHR) {
				$('#new-prof-form').fadeOut(500);
				$('#new-prof-form input').value = '';
				
				$('#prof-added').fadeIn(500);

				setTimeout(function() {
					$('#prof-added').fadeOut(600);
				}, 2000);
				
				load_profs();
			}
		});
	});
	
	$('#prof-cancel-btn').click(function(e) {
		e.preventDefault();
		$('#new-prof-form input').value = '';
		$('#new-prof-form').fadeOut(100);
		//form.addClass('hidden');
	});
	
	load_profs();
});

function load_profs() {
	var url = $('#prof-list').data('list-url');
	
	$.ajax({
		url: url,
		type: 'post',
		success: function(data, textStatus, jqXHR) {
			var json_data = JSON.parse(data);
			populate_prof_list(json_data);
		},
		error: function(jqXHR) {
			populate_prof_list([]);
			console.log(jqXHR);
		}
	});
}

function populate_prof_list(profs) {
	var prefix = '<div>';
	var suffix = '</div>';
	
	var html = '';
	
	$.each(profs, function(i, prof) {
		html += '<div class="prof-list-row row-content" data-id="' + prof['id'] + '">';
		html += '<hr/>';
		html += '<i class="prof-del row-cell editable editable-click fa fa-trash"></i>';
		html += '<div class="prof-name row-cell editable editable-click">';
		html += prof['name'] + '</div>';
		html += '<div class="prof-uid row-cell editable editable-click">';
		html += prof['uid'] + '</div>';
		html += '</div>';
	});
	
	$('#prof-list-data div').remove();
	$('#prof-list-data').append(html);
	
	var url = $('#prof-list').data('edit-url');
	$('#prof-list-data .prof-name').editable({
		url: url,
		pk: function() { return $(this).parent().data('id'); },
		name: 'name',
		title: 'Enter Professor Name',
		success: function(response, newValue) {}
	});
	$('#prof-list-data .prof-name').editable('option', 'validate', function(v) {
		if(!v) return 'Required field!';
	});
	
	$('#prof-list-data .prof-uid').editable({
		url: url,
		pk: function() { return $(this).parent().data('id'); },
		name: 'uid',
		title: 'Enter Room Capacity',
		success: function(response, newValue) {}
	});
	
	$('#prof-list-data .prof-del').editable({
		type: 'checklist',
		display: false,
		value: '',
		source: {'1': 'Remove this professor?'},
		url: url,
		pk: function() { return $(this).parent().data('id'); },
		name: 'remove',
		title: 'Remove professor',
		success: function(response, newValue) {
			if (newValue == '1') {
				var row = $(this).parent();
				row.fadeOut(500);
				row.remove();
			}
		}
	});
}
