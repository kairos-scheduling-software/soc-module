$(function() {
	$('body').on('click', '.time-group-name', function() {
		$('.time-group-row.selected').removeClass('selected');
		var group_row = $(this).parent();
		group_row.addClass('selected');
		var id = group_row.data('id');
		$('#time-list-edit').editable('option', 'pk', id);
		if (id == '-1') {
			$('#time-list-edit').editable('option', 'disabled', true);
		} else {
			$('#time-list-edit').editable('option', 'disabled', false);
		}
		load_times();
	});
	
	$('#import-time-form').on('submit', function(e) {
		e.preventDefault();
		
		var data = new FormData($(this)[0]);
		//data.append('times_file', $(this).children('input[name="times_data"]').prop('files')[0]);
		
		//console.log(data);
		
		var url = $(this).data('url');
		
		$.ajax({
			url: url,
			type: 'post',
			data: data,
			contentType: false,
			processData: false,
			success: function(data, textStatus, jqXHR) {
				console.log(data);
			}
		});
	});
	
	$('#add-time-btn').click(function() {
		$('#new-time-form').fadeIn(100);
		$('#new-time-name').focus();
	});
	
	$('#time-added').hide();
	
	$('#new-time-form').on('submit', function(e) {
		e.preventDefault();
		var form = $(this);
		
		var data = form.serializeArray();
		var url = form.data('url');
		
		$.ajax({
			url: url,
			type: 'post',
			data: data,
			success: function(data, textStatus, jqXHR) {
				$('#new-time-form').fadeOut(500);
				$('#new-time-form input').value = '';
				
				$('#time-added').fadeIn(500);

				setTimeout(function() {
					$('#time-added').fadeOut(600);
				}, 2000);
				
				load_times();
			}
		});
	});
	
	$('#time-cancel-btn').click(function(e) {
		e.preventDefault();
		$('#new-time-form input').value = '';
		$('#new-time-form').fadeOut(100);
		//form.addClass('hidden');
	});
	
	$('#add-group-btn').editable({
		display: false,
		pk: '-1',
		value: '',
		name: 'name',
		title: 'Enter New Time Group Name',
		success: function(response, newValue) {
			console.log(response);
			console.log(newValue);
			load_time_groups();
			return '';
		}
	});
	$('#add-group-btn').editable('option', 'validate', function(v) {
		if(!v) return 'Required field!';
	});
	
	var time_list_url = $('#time-list').data('list-url');
	$('#time-list-edit').editable({
		display: false,
		type: 'checklist',
		placement: 'right',
		value: '',
		source: time_list_url,
		sourceOptions: {
			type: 'post',
			data: {
				editableList: true
			}
		},
		name: 'times',
		title: 'Select Times For This Group',
		success: function(response, newValue) {
			load_times();
		}
	});
	
	load_time_groups();
});

function load_time_groups() {
	var url = $('#time-group-list').data('url');
	
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

function load_times() {
	var grp_id = $('.time-group-row.selected').data('id');
	var url = $('#time-list').data('list-url');
	
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
			populate_time_list(json_data);
		},
		error: function(jqXHR) {
			populate_time_list([]);
			console.log(jqXHR);
		}
	});
}

function populate_group_list(groups) {
	var html = '<div class="time-group-row row-content selected" data-name="all" data-id="-1">';
	html += '<div class="time-group-name row-cell" style="width:100%">All</div></div>';
	
	$.each(groups, function(i, grp) {
		html += '<div class="time-group-row row-content" data-id="' + grp['id'] + '">';
		html += '<div class="time-group-name row-cell">' + grp['name'] + '</div>';
		html += '<div class="time-group-del row-cell fa fa-trash"></div>';
		html += '</div>';
	});
	
	$('#time-group-list div').remove();
	$('#time-group-list').append(html);
	
	var url = $('#time-list-edit').data('url');
	$('.time-group-del').editable({
		url: url,
		type: 'checklist',
		display: false,
		value: '',
		source: {'1': 'Delete this time group?'},
		pk: function() { return $(this).parent().data('id'); },
		name: 'remove',
		title: 'Time group delete',
		success: function(response, newValue) {
			if (newValue == '1') {
				var row = $(this).parent();
				row.fadeOut(500);
				row.remove();
			}
		}
	});
	
	$('.time-group-row.selected .time-group-name').click();
}

function populate_time_list(times) {
	var html = '';
	var ids = [];
	
	$.each(times, function(i, rm) {
		ids.push(rm['id']);
		html += '<div class="time-list-row row-content" data-id="' + rm['id'] + '">';
		html += '<hr/>';
		html += '<i class="time-del row-cell editable editable-click fa fa-trash"></i>';
		html += '<div class="time-start row-cell editable editable-click">';
		html += rm['starttm'] + '</div>';
		html += '<div class="time-length row-cell editable editable-click">';
		html += rm['length'] + '</div>';
		html += '<div class="time-days row-cell editable editable-click">';
		html += rm['days'] + '</div>';
		html += '</div>';
	});
	
	$('#time-list-data div').remove();
	$('#time-list-data').append(html);
	
	var url = $('#time-list').data('edit-url');
	//~ $('#time-list-data .time-name').editable({
		//~ url: url,
		//~ pk: function() { return $(this).parent().data('id'); },
		//~ name: 'name',
		//~ title: 'Enter Time Name',
		//~ success: function(response, newValue) {}
	//~ });
	//~ $('#time-list-data .time-name').editable('option', 'validate', function(v) {
		//~ if(!v) return 'Required field!';
	//~ });
	//~ 
	//~ $('#time-list-data .time-capacity').editable({
		//~ url: url,
		//~ pk: function() { return $(this).parent().data('id'); },
		//~ name: 'capacity',
		//~ title: 'Enter Time Capacity',
		//~ success: function(response, newValue) {}
	//~ });
	
	$('#time-list-data .time-del').editable({
		type: 'checklist',
		display: false,
		value: '',
		source: {'1': 'Delete this time block?'},
		url: url,
		pk: function() { return $(this).parent().data('id'); },
		name: 'remove',
		title: 'Time delete',
		success: function(response, newValue) {
			if (newValue == '1') {
				var row = $(this).parent();
				row.fadeOut(500);
				row.remove();
			}
		}
	});
	
	$('#time-list-edit').editable('option', 'value', ids.toString());
}
