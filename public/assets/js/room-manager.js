$(function() {
	$('#room-list-edit').editable('option', 'disabled', true);
	$('body').on('click', '.room-group-row', function() {
		$('.room-group-row.selected').removeClass('selected');
		$(this).addClass('selected');
		var id = $(this).data('id');
		if (id == '-1') {
			$('#room-list-edit').editable('option', 'disabled', true);
		} else {
			$('#room-list-edit').editable('option', 'disabled', false);
		}
		load_rooms(id);
	});
	
	//$('#new-room-name').editable();
	//$('#new-room-capacity').editable();
	
	$('#add-room-btn').click(function() {
		$('#new-room-form').removeClass('hidden');
		$('#new-room-name').focus();
	});
	
	$('#room-added').hide();
	
	$('#new-room-form').on('submit', function(e) {
		e.preventDefault();
		var form = $(this);
		
		var data = form.serializeArray();
		var url = form.data('url');
		
		$.ajax({
			url: url,
			type: 'post',
			data: data,
			success: function(data, textStatus, jqXHR) {
				$('#new-room-form input').value = '';
				form.addClass('hidden');
				setTimeout(function() {
					$('#room-added').css('display', 'inline');
				}, 700);

				setTimeout(function() {
					$('#room-added').fadeOut(600);
				}, 2500);
			}
		});
	});
	
	$('#add-group-btn').editable({
		display: false,
		pk: '-1',
		value: '',
		name: 'name',
		title: 'Enter New Room Group Name',
		success: function(response, newValue) {
			console.log(response);
			console.log(newValue);
			load_room_groups();
		}
	});
	$('#add-group-btn').editable('option', 'validate', function(v) {
		if(!v) return 'Required field!';
	});
	
	$('#room-list-edit').editable({
		display: false,
		pk: function() { return $('#room-list').data('id'); },
		value: '',
		name: 'rooms',
		title: 'Select Rooms For This Group',
		success: function(response, newValue) {
			console.log(response);
			console.log(newValue);
		}
	});
	
	load_room_groups();
});

function load_room_groups() {
	var url = $('#room-group-list').data('url');
	
	$.ajax({
		url: url,
		type: 'post', 
		success: function(data, textStatus, jqXHR) 
		{
			var json_data = JSON.parse(data);
			populate_group_list(json_data);
		},
		error: function(jqXHR, textStatus, errorThrown) 
		{
			//alert('');
			console.log('Can\'t retrieve group list');
			console.log('textStatus: ' + textStatus);
		}
	});
}

function load_rooms(grp_id) {
	var url = $('#room-list').data('list-url');
	
	$('#room-list').data('id',  grp_id);
	var data = Object.create(null);
	if (grp_id != '-1') {
		data['grp'] = grp_id;
	}
	console.log(data);
	$.ajax({
		url: url,
		type: 'post',
		data: data,
		success: function(data, textStatus, jqXHR) {
			var json_data = JSON.parse(data);
			populate_room_list(json_data);
		},
		error: function(jqXHR) {
			populate_room_list([]);
		}
	});
}

function populate_group_list(groups) {
	var prefix = '<div>';
	var suffix = '</div>';
	
	var html = '<div class="room-group-row row-content selected" data-name="all" data-id="-1"><div class="row-cell" style="display:inherit">All</div></div>';
	
	$.each(groups, function(i, grp) {
		html += '<div class="room-group-row row-content" data-id="' + grp['id'] + '">';
		html += '<div class="row-cell" style="display:inherit">' + grp['name'] + '</div></div>';
	});
	
	$('#room-group-list div').remove();
	$('#room-group-list').append(html);
	$('.room-group-row.selected').click();
}

function populate_room_list(rooms) {
	var prefix = '<div>';
	var suffix = '</div>';
	
	var html = '';
	
	$.each(rooms, function(i, rm) {
		html += '<div class="room-list-row row-content" data-id="' + rm['id'] + '">';
		html += '<hr/>';
		html += '<i class="fa fa-trash room-del"></i>';
		html += '<div class="room-name row-cell editable editable-click">';
		html += rm['name'] + '</div>';
		html += '<div class="room-capacity row-cell editable editable-click">';
		html += rm['capacity'] + '</div>';
		html += '</div>';
	});
	
	$('#room-list-data div').remove();
	$('#room-list-data').append(html);
	
	var url = $('#room-list').data('edit-url');
	$('#room-list-data .room-name').editable({
		url: url,
		pk: function() { return $(this).parents('.room-list-row').data('id'); },
		name: 'name',
		title: 'Enter Room Name',
		success: function(response, newValue) {
			console.log(response);
			console.log(newValue);
		}
	});
	$('#room-list-data .room-name').editable('option', 'validate', function(v) {
		if(!v) return 'Required field!';
	});
	
	$('#room-list-data .room-capacity').editable({
		url: url,
		pk: function() { return $(this).parents('.room-list-row').data('id'); },
		name: 'capacity',
		title: 'Enter Room Capacity',
		success: function(response, newValue) {
			console.log(response);
			console.log(newValue);
		}
	});
}
