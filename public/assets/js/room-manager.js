$(function() {
	$('body').on('click', '.room-group-name', function() {
		$('.room-group-row.selected').removeClass('selected');
		var group_row = $(this).parent();
		group_row.addClass('selected');
		var id = group_row.data('id');
		$('#room-list-edit').editable('option', 'pk', id);
		if (id == '-1') {
			$('#room-list-edit').editable('option', 'disabled', true);
		} else {
			$('#room-list-edit').editable('option', 'disabled', false);
		}
		load_rooms();
	});
	
	//$('#new-room-name').editable();
	//$('#new-room-capacity').editable();
	
	$('#add-room-btn').click(function() {
		$('#new-room-form').fadeIn(100);
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
				$('#new-room-form').fadeOut(500);
				$('#new-room-form input').value = '';
				
				$('#room-added').fadeIn(500);

				setTimeout(function() {
					$('#room-added').fadeOut(600);
				}, 2000);
				
				load_rooms();
			}
		});
	});
	
	$('#room-cancel-btn').click(function(e) {
		e.preventDefault();
		$('#new-room-form input').value = '';
		$('#new-room-form').fadeOut(100);
		//form.addClass('hidden');
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
			return '';
		}
	});
	$('#add-group-btn').editable('option', 'validate', function(v) {
		if(!v) return 'Required field!';
	});
	
	var room_list_url = $('#room-list').data('list-url');
	$('#room-list-edit').editable({
		display: false,
		type: 'checklist',
		placement: 'right',
		value: '',
		source: room_list_url,
		sourceOptions: {
			type: 'post',
			data: {
				fields_mapping: {
					id: 'value',
					name: 'text'
				}
			}
		},
		name: 'rooms',
		title: 'Select Rooms For This Group',
		success: function(response, newValue) {
			load_rooms();
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

function load_rooms() {
	var grp_id = $('.room-group-row.selected').data('id');
	var url = $('#room-list').data('list-url');
	
	var data = Object.create(null);
	if (grp_id != '-1') {
		data['grp'] = grp_id;
	}
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
			console.log(jqXHR);
		}
	});
}

function populate_group_list(groups) {
	var prefix = '<div>';
	var suffix = '</div>';
	
	var html = '<div class="room-group-row row-content selected" data-name="all" data-id="-1">';
	html += '<div class="room-group-name row-cell" style="width:100%">All</div></div>';
	
	$.each(groups, function(i, grp) {
		html += '<div class="room-group-row row-content" data-id="' + grp['id'] + '">';
		html += '<div class="room-group-name row-cell">' + grp['name'] + '</div>';
		html += '<div class="room-group-del row-cell fa fa-trash"></div>';
		html += '</div>';
	});
	
	$('#room-group-list div').remove();
	$('#room-group-list').append(html);
	
	var url = $('#room-list-edit').data('url');
	$('.room-group-del').editable({
		url: url,
		type: 'checklist',
		display: false,
		value: '',
		source: {'1': 'Delete this room group?'},
		pk: function() { return $(this).parent().data('id'); },
		name: 'remove',
		title: 'Room group delete',
		success: function(response, newValue) {
			if (newValue == '1') {
				var row = $(this).parent();
				row.fadeOut(500);
				row.remove();
			}
		}
	});
	
	$('.room-group-row.selected .room-group-name').click();
}

function populate_room_list(rooms) {
	var prefix = '<div>';
	var suffix = '</div>';
	
	var html = '';
	var ids = [];
	
	$.each(rooms, function(i, rm) {
		ids.push(rm['id']);
		html += '<div class="room-list-row row-content" data-id="' + rm['id'] + '">';
		html += '<hr/>';
		html += '<i class="room-del row-cell editable editable-click fa fa-trash"></i>';
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
		pk: function() { return $(this).parent().data('id'); },
		name: 'name',
		title: 'Enter Room Name',
		success: function(response, newValue) {}
	});
	$('#room-list-data .room-name').editable('option', 'validate', function(v) {
		if(!v) return 'Required field!';
	});
	
	$('#room-list-data .room-capacity').editable({
		url: url,
		pk: function() { return $(this).parent().data('id'); },
		name: 'capacity',
		title: 'Enter Room Capacity',
		success: function(response, newValue) {}
	});
	
	$('#room-list-data .room-del').editable({
		type: 'checklist',
		display: false,
		value: '',
		source: {'1': 'Delete this room?'},
		url: url,
		pk: function() { return $(this).parent().data('id'); },
		name: 'remove',
		title: 'Room delete',
		success: function(response, newValue) {
			if (newValue == '1') {
				var row = $(this).parent();
				row.fadeOut(500);
				row.remove();
			}
		}
	});
	
	$('#room-list-edit').editable('option', 'value', ids.toString());
}
