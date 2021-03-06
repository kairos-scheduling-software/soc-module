var five_min_height;
var time_block_w;
var all_blocks = {};
var day_columns = [];
var blocks_to_update = [];
var sched_id;
var col_counts = [];
var course_list = Object.create(null);
var temp_indices = [];
var right_panel_open = false;

var rooms;
var room_groups;
var professors;

$(function(){

	initialize_column_matrix();

	sched_id = $('#hidden-data').data('schedid');
	var cursor_img = $('#hidden-data').data('cursor');

	$('#search-clear').click(function(){
		$('#class-search').val("");

		$('div.scheduled-class').css({
			backgroundColor: '#0099FF', 
			opacity: '1', 
			boxShadow: ''});
	});

	$('body').on('submit', '#new-class-form', function(e) {
		e.preventDefault();
		var form = $(this);
		var blocks = form.data('block');
		var time_id = blocks.data('time');
		var class_name = form.children('input[name="class_name"]').val().trim();
		
		blocks.popover('destroy');
		
		var url = $('#edit-class-panel').data('url');
		var data = {
			sched_id: sched_id,
			mode: 'add-class',
			class_name: class_name,
			enroll: form.children('input[name="enroll"]').val(),
			room_id: form.children('select[name="room"]').val(),
			grp_id: form.children('select[name="room_group"]').val(),
			prof_id: form.children('select[name="prof"]').val()
		};
		if (time_id != undefined) data['time_id'] = time_id;
		else {
			data['start'] = blocks.data('start');
			data['length'] = blocks.data('length');
			data['days'] = blocks.data('days');
		}
		
		$.ajax({
			url:		url,
			type: 		"POST",
			data: 		data,
			beforeSend: function() {
				$('#sched-ok').hide();
				$('#sched-bad').hide();
				$('#checking-sched').show();
			},
			success: 	function(new_data, textStatus, jqXHR) {
				//$('#checking-sched').hide();
				var json_data = JSON.parse(new_data);
				
				blocks.removeClass('new-block');
				data['class_id'] = json_data['newId'];
				if (time_id == undefined) data['time_id'] = json_data['newTime'];
				update_class_info(blocks, data);
				course_list[data['class_name']] = data['class_id'];
				
				// Update the matrix
				update_column_matrix(blocks, "busy");
				
				// Set-up draggable for the new blocks
				update_scheduled_class_draggables(blocks);
				
				handle_class_conflicts(json_data);
				//~ if (json_data['wasFailure'])
				//~ {
					//~ console.log('was failure: true');
					//~ $('#sched-bad').show();
					//~ $('#conflict-section').show();
				//~ }
				//~ else
				//~ {
					//~ console.log('was failure: false');
					//~ $('#sched-ok').show();
				//~ }
				refresh_autocomplete();
			},
			error: 		function(jqXHR, textStatus, errorThrown) {
				console.log(JSON.stringify(jqXHR));
				$('#checking-sched').hide();
				$('#sched-bad').show();
				blocks.remove();
			}
		});

		return true;
	});

	$('body').on('submit', '#edit-panel-form', function(e) {
		e.preventDefault();
		var form = $(this);
		
		var constraints = [];
		$.each(form.find('.constraint-row'), function(i, con_row) {
			var key =$(con_row).find('.form-control[name="constraint-key"]').val();
			var val = $(con_row).find('.form-control[name="constraint-val"]').val();
			if (key != '' && val != '') {
				constraints.push({key: key, value: val});
			}
		});
		
		var class_data = {
			sched_id: sched_id,
			mode: 'edit-class',
			class_id: form.find('.form-control[name="class_id"]').val(),
			class_name: form.find('.form-control[name="class_name"]').val(),
			enroll: form.find('.form-control[name="enroll"]').val(),
			prof_id: form.find('.form-control[name="prof"]').val(),
			room_id: form.find('.form-control[name="room"]').val(),
			grp_id: form.find('.form-control[name="room_group"]').val(),
			constraints: JSON.stringify(constraints)
		};
		
		console.log(class_data);
		$.ajax({
			url: $('#edit-class-panel').data('url'),
			type: 'post',
			data: class_data,
			beforeSend: function() {
				$('#sched-ok').hide();
				$('#sched-bad').hide();
				$('#checking-sched').show();
			},
			success: 	function(data, textStatus, jqXHR) {
				//$('#checking-sched').hide();
				var json_data;
				if (data != '') json_data = JSON.parse(data);
				else json_data = Object.create(null);
				
				var blocks = get_class(class_data['class_id']);
				update_class_info(blocks, class_data);
				//blocks.click();
				
				handle_class_conflicts(json_data);
				//~ if (json_data['wasFailure'])
				//~ {
					//~ console.log('was failure: true');
					//~ $('#sched-bad').show();
					//~ $('#conflict-section').show();
				//~ }
				//~ else
				//~ {
					//~ console.log('was failure: false');
					//~ $('#sched-ok').show();
				//~ }
			},
			error: 		function(jqXHR, textStatus, errorThrown) {
				console.log(JSON.stringify(jqXHR));
				$('#checking-sched').hide();
				$('#sched-bad').show();
				$('#cancel-edit-panel').click();
			}
		});
		return true;
	});
	resize_all();
	fetch_time_blocks();
	fetch_schedule(sched_id);
	//load_schedule();
	

	$('.panel-collapse').on('show.bs.collapse', function() {
		$(this).closest('div.panel').find('span.accordion-open').show();
		$(this).closest('div.panel').find('span.accordion-closed').hide();
	});

	$('.panel-collapse').on('hide.bs.collapse', function() {
		$(this).closest('div.panel').find('span.accordion-open').hide();
		$(this).closest('div.panel').find('span.accordion-closed').show();
	});

	$('.panel-heading').click(function() {
		$('.panel-collapse').each(function() {
			if ($(this).hasClass('in'))
				$(this).collapse('hide');
		});

		$(this).closest('div.panel').find('.panel-collapse').first().collapse('toggle');
	});

	$('.panel-heading').mouseenter(function(e) {
		$(this).addClass('cursor-link');
	}).mouseleave(function(e) {
		$(this).removeClass('cursor-link');
	});

	$('.trash-can').droppable({
		hoverClass: "trash-hover"
	});

	$('#cust-start-time').timepicker({
		minuteStep: 5,
		showInputs: false,
		disableFocus: true
	}).on('show.timepicker', function(e) {
		$('i.icon-chevron-up').addClass('fa fa-chevron-up');
		$('i.icon-chevron-down').addClass('fa fa-chevron-down');
	});

	$('#cust-end-time').timepicker({
		minuteStep: 5,
		showInputs: false,
		disableFocus: true
	}).on('show.timepicker', function(e) {
		$('i.icon-chevron-up').addClass('fa fa-chevron-up');
		$('i.icon-chevron-down').addClass('fa fa-chevron-down');
	});

	$('#start-time-clock').click(function(e) {
		$('#cust-start-time').timepicker('showWidget');
	});

	$('#end-time-clock').click(function(e) {
		$('#cust-end-time').timepicker('showWidget');
	});
	
	$('#add-custom-class-btn').click(function(e) {
		if ($('#new-class-form').length > 0) return;
		var days_arr = [];
		var count = 0;
		$.each($('#custom-days').find('input'), function(i, box) {
			count += 1;
			if ($(box).prop('checked')) days_arr.push(count);
			//console.log($(box).prop('checked'));
		});
		var days = parse_days(days_arr);
		if (days.length == 0) return;
		var start_str = $('#cust-start-time').val();
		var end_str = $('#cust-end-time').val();
		
		var start_h, start_m, end_h, end_m;
		
		var start_tm = start_str.substring(0, start_str.indexOf(' ')).replace(':', '');
		if (start_tm.length < 4) start_tm = '0' + start_tm;
		start_h = parseInt(start_tm.substring(0, 2));
		start_m = parseInt(start_tm.substring(2));
		if (start_str.endsWith('PM')) {
			if (start_h < 12) start_h += 12;
			start_tm = start_h + start_tm.substring(2);
		}
		
		var end_tm = end_str.substring(0, end_str.indexOf(' ')).replace(':', '');
		if (end_tm.length < 4) end_tm = '0' + end_tm;
		end_h = parseInt(end_tm.substring(0, 2));
		end_m = parseInt(end_tm.substring(2));
		if (end_str.endsWith('PM')) {
			if (end_h < 12) end_h += 12;
		}
		
		var length = (end_h - start_h) * 60 + (end_m - start_m);
		
		var blocks = [];
		$.each(days, function(i, day) {
			var ddd = day.substring(1, 4);
			//var constraints = ev['constraints'];
			//if (constraints == null) constraints = [];
			
			var left = 0;
			var top = 0;
			var v_offset = get_vertical_offset(start_tm);
			top += (v_offset * five_min_height);
			var horiz = get_horizontal_offset(v_offset, length, ddd, false);
			
			if (horiz == -1)
			{
				console.log("Horizontal failed");
				return;
			}
			left += horiz * time_block_w;
			
			var el = jQuery('<div></div>', {
				//text: ev['name'],
				'class': 'scheduled-class',
				//'data-id': ev['id'],
				//'data-time': ev['etime_id'],
				'data-days': days_arr.join('|'),
				'data-ddd': ddd,
				'data-start': start_tm,
				'data-length': length,
				'data-col_index': (horiz + 1),
				'style': "left: " + left + "px; top: " + top + "px; position: absolute;"
				//'data-room_id': (ev['room_id'] != null ? ev['room_id'] : -1),
				//'data-grp_id': (ev['room_group_id'] != null ? ev['room_group_id'] : -1),
				//'data-enroll': ev['enroll_cap'],
				//'data-prof_id': ev['professor']
			});
			//el.data('constraints', constraints);
			el.data('constraints', []);
			el.css({
				height: (el.data('length') / 5) * five_min_height,
				width: time_block_w +'px'
			});
			blocks.push(el);
			$(day).append(el);
		});
		
		blocks = $(blocks).map(function () { return this.toArray(); } );
		$('#outer-container').css('width', (total_cols() * (time_block_w + 2)) + 'px');
		
		//var time_id = $(this).data('time');
		var params = [];
		var block = blocks[0];
		params["col"] = $(block).data('col_index');
		params["days"] = '' + $(block).data('days');
		params["start"] = start_tm;
		params["start_time"] = start_tm;
		params["length"] = length;
		params['custom_time'] = true;
		
		var content = get_popover_content(params);

		// Show popover
		var pos = parseFloat($(block).css('top')) + $(block).height()/2;
		var el = $(block);

		$(block).popover({
			trigger: "manual",
			html: true,
			content: content
		}).on('shown.bs.popover', function() {
			if(parseInt($('.popover').css('top')) < 0)
			{
				$('.popover').css('top', 0);
				$('.arrow').css('top', pos + 'px');
			}
			
			$('#new-class-form').data('block', blocks);
			
			$('#cancel-add-class').click(function() {
				el.popover('destroy');
				blocks.remove();
			});

		}).popover('show');
		
	});
	
	$('#view-conflicts').click(function(e) {
		e.preventDefault();

		if (!panel_is_open)
			$('#toggle-toolbox').click();

		$('.panel-collapse').each(function() {
			if ($(this).hasClass('in') && ($(this).attr('id') != 'conflict-list'))
				$(this).collapse('hide');
		});

		if (!($('#conflict-list').hasClass('in')))
			$('#conflict-list').collapse('show');
	});
	
	// Listen for 'ctrl + f' and override the browser's default search
	$(window).keydown(function(e) {
		if (e.which == "70" && e.ctrlKey)
		{
			// Prevent the browser's default 'ctrl + f' behavior
			e.preventDefault();

			// Open the toolbox
			if (!panel_is_open)
				$('#toggle-toolbox').click();

			// Close any open toolbox sections and open the 'On the Schedule' accordion item
			$('.panel-collapse').each(function() {
				if ($(this).hasClass('in') && ($(this).attr('id') != 'collapse-six'))
					$(this).collapse('hide');
			});

			if (!($('#collapse-six').hasClass('in')))
				$('#collapse-six').collapse('show');

			// Place focus in the text input
			$('#class-search').focus();
		}
	});
	
	$('#outer-container').css('width', (total_cols() * (time_block_w + 2)) + 'px');
	
	$('body').on('click', '.conflict-row', function(e) {
		var id1 = $(this).data('id1');
		var id2 = $(this).data('id2');
		$('.scheduled-class').css({
			opacity: 0.2,
			backgroundColor: '#0099FF',
			boxShadow: ''
		});
		
		$('.id-' + id1).css({
			opacity: 1,
			backgroundColor: '#DA4747',
			boxShadow: '2px 2px 4px #363636, 0 0 6px #363636'
		});
		
		$('.id-' + id2).css({
			opacity: 1,
			backgroundColor: '#DA4747',
			boxShadow: '2px 2px 4px #363636, 0 0 6px #363636'
		});
		
		setTimeout(function() {
			$('div.scheduled-class').css({
				backgroundColor: '#0099FF', 
				opacity: '1', 
				boxShadow: ''
			});
		}, 2000);
	});
	
	$('body').on('click', '.scheduled-class', function(e) {
		e.preventDefault();

		var form = $('#edit-panel-form');
		form.find('input[name="class_id"]').val('');
		
		var scheduled_class = $(this);
		var class_data = get_class_info(scheduled_class);
		
		form.find('input[name="class_name"]').val(class_data['class_name']);
		form.find('input[name="enroll"]').val(class_data['enroll']);
		form.find('select[name="prof"]').val(class_data['prof_id']);
		
		var grp_select = form.find('select[name="room_group"]');
		var room_select = form.find('select[name="room"]');
		var prof_select = form.find('select[name="prof"]');
		//~ $.ajax({
			//~ url: grp_select.data('url'),
			//~ type: 'post',
			//~ success: function(data, textStatus, jqXHR) {
				//~ var grps = JSON.parse(data);
				//~ 
			//~ }
		//~ });
		
		// Setup initial values for room_group and room select (right-side-bar)
		var html = '<option value="-1">All</option>';
		$.each(room_groups, function(grp_id, grp) {
			html += '<option value="' + grp_id + '">' + grp.name + '</option>';
		});
		grp_select.html(html);
		
		html = '';
		$.each(rooms, function(i, rm) {
			html += '<option value="' + rm['id'] + '">' + rm['name'] + '</option>';
		});
		room_select.html(html);
		
		html = '';
		$.each(professors, function(i, prof) {
			var id = prof['id'];
			var prof_name = prof['name'];
			html += '<option value="' + id + '">' + prof_name + '</option>';
		});
		prof_select.html(html);
		
		prof_select.val(class_data['prof_id']);
		grp_select.val(class_data['grp_id']).trigger('change');
		room_select.val(class_data['room_id']);
		
		$('#constraint-table .constraint-row').remove();
		$.each(scheduled_class.data('constraints'), function(i, con) {
			add_constraint_row(con['key'], con['value']);
		});
		add_constraint_row('', '');
		
		form.find('input[name="class_id"]').val(class_data['class_id']);

		if(!right_panel_open)
		{
			if(panel_is_open)
			{
				$('#toggle-container').animate({marginLeft: 0}, {duration: 200}); 
				$.panelslider.close(function() { $('#toggle-toolbox').html('<i class="fa fa-chevron-right"></i>'); });
				panel_is_open = false;
			}

			$('#right-side-bar').show('slide', {direction: 'right', duration: 200});
			right_panel_open = true;
		}
		$('#main-container').css('margin-right', ($('#right-side-bar').outerWidth() + 50) + 'px');
	});
	
	$('#add-const-btn').click(function(e) {
		e.preventDefault();
		add_constraint_row('', '');
	});
	
	$('body').on('click', '.constraint-del', function(e) {
		e.preventDefault();
		$(this).parent().remove();
	});
	
	$('#cancel-edit-panel').click(function(e) {
		e.preventDefault();
		$('#edit-panel-form').find('.form-control').val('');
		$('#close-right-panel').click();
	});
	
	$('#close-right-panel').click(function(e) {
		e.preventDefault();

		$('#right-side-bar').hide('slide', {direction: 'right', duration: 200});
		$('#main-container').css('margin-right', '');
		right_panel_open = false;
	});
});

$(document).ready(function () {
	// Update room_name on room_group_name changes
	$('body').on('change', 'select[name="room_group"]', function(e) {
		var grp_id = $(this).children('option:selected')[0].value;
		var rm_list;
		if (grp_id == -1) {
			rm_list = rooms;
		} else {
			rm_list = room_groups[grp_id];
		}
		var html = '';
		$.each(rm_list, function(i, rm) {
			html += '<option value="' + rm['id'] + '">' + rm['name'] + '</option>';
		});
		var grp_select = $(this);
		var rm_select = grp_select.closest('form').find('select[name="room"]');
		rm_select.children('option').remove();
		rm_select.append(html);
	});
});

/**
 * Dynamically resizes all elements on the page
 */
function resize_all()
{/*
	// Schedule container
	var sched_container_w = $('#hg-center').width() - 50 - $('#time-labels').width();
	var sched_container_h = $(window).height() 
							- $('.top-buffer').outerHeight(true) 
							- $('#sched-name').outerHeight(true) 
							- $('#sched-col-headers').outerHeight(true)
							- $('#trash-container').outerHeight(true)
							- parseInt($('#page_footer').css('height'));

	five_min_height = sched_container_h / 144;

	$('#sched-container').css('max-width', sched_container_w + 'px');
	$('#sched-container').css('min-width', sched_container_w + 'px');
	$('#sched-container').css('width', sched_container_w + 'px');
	$('#sched-container').css('max-height', sched_container_h + 'px');
	$('#sched-container').css('min-height', sched_container_h + 'px');
	$('#sched-container').css('height', sched_container_h + 'px');

	// Time Labels
	$('#time-labels').css('height', sched_container_h + 8 + 'px');

	// Day Columns
	var col_height = ($('#sched-container').innerHeight() - 5);
	$('.sched-day-column').css('height', col_height + 'px');
	var col_width = $('#sched-container').innerWidth() / 5;
	col_width -= 5;
	$('.sched-day-column').css('width', col_width + 'px');
	//$('.sched-col-header').css('width', col_width + 'px');
	time_block_w = parseInt(col_width / 6);

	// Toolbox blocks
	$('.fifty-min-blk').css('height', (10 * five_min_height));
	$('.eighty-min-blk').css('height', (16 * five_min_height));
	*/

	var sched_height = Math.max($(window).height() 
							- $('.top-buffer').outerHeight(true) 
							- $('#sched-name').outerHeight(true) 
							- $('#sched-col-headers').outerHeight(true)
							- $('#bottom-container').outerHeight(true)
							- parseInt($('#page_footer').css('height')), 576);

	//$('#main-container').css('width', $(window).width() + 'px');
	time_block_w = 40;//$('#main-container').width() / 35;

	$('#time-labels').css('height', sched_height + 'px');
	five_min_height = $('#inner-container').height() / 144;

	//$('.fifty-min-blk').css('height', (10 * five_min_height));
	//$('.eighty-min-blk').css('height', (16 * five_min_height));
	
	$.each(['mon', 'tue', 'wed', 'thu', 'fri'], function(i, day) {
		$('#' + day + '-col').css('width', col_counts[day] * (time_block_w + 2) + 'px');
	});
	$('#outer-container').css('width', (total_cols() * (time_block_w + 2) + 60) + 'px');

	$('#left-side-bar').css('height', ($(window).height() - 40) + 'px');

	//~ $('.class-name-container').each(function() {
		//~ var course = $(this);
		//~ var div = course.closest('div.scheduled-class');
		//~ var p = div.height();
		//~ p = (p - course.outerHeight(true)) / 4;
//~ 
		//~ if(p >= 7)
		//~ div.css("padding-top", p + 'px');
	//~ });
}

function parse_days(json_days)
{
	json_days = '' + json_days;
	var days = [];
	
	if (json_days.search('col') >= 0) {
		days = json_days.split(',');
	} else {
		if (json_days.indexOf("1") >= 0)
			days.push("#mon-col");
		if (json_days.indexOf("2") >= 0)
			days.push("#tue-col");
		if (json_days.indexOf("3") >= 0)
			days.push("#wed-col");
		if (json_days.indexOf("4") >= 0)
			days.push("#thu-col");
		if (json_days.indexOf("5") >= 0)
			days.push("#fri-col");
	}

	return days;
}

/**
 * Returns the proper value for the CSS "top" property for a give time block
 * 
 * @param time_string: A start-time string of the form hhmm (e.g. 0805 means 8:05 AM)
 */
function get_vertical_offset(time_string)
{
	time_string = "" + time_string;
	var hr_str = time_string.substring(0,2);
	var min_str = time_string.substring(2);
	var hr = parseInt(hr_str);
	var min = parseInt(min_str);

	//console.log("Time String: " + time_string + "  =>  HOUR: " + hr + ", MIN: " + min);
	var offset_factor = ((hr - 8) * 12) + (min / 5);

	return offset_factor;
}

/**
 * Returns the correct column to append a given time-block to
 *
 * @param vertical: The previously computed vertical offset of this block (See 'get_vertical_offset()')
 * @param length: The duration, in minutes, of this time block
 * @param day: The 3 character day abbreviation (e.g. mon, tue, etc.)
 */
function get_horizontal_offset(vertical, length, day, loading_mode)
{
	var col_index = day + "1";
	var col_count = col_counts[day];
	var col_num = 1;
	var update = [];

	for(var i = vertical; i < vertical + (length/5); i++)
	{
		if (day_columns[col_index][i] == "busy")
		{
			col_num++;
			col_index = day + "" + col_num;
			i = vertical;

			if (col_num > col_count)
			{
				var w = $('#outer-container').width();
				$('#outer-container').css('width', (w + time_block_w + 20) + 'px');
				add_matrix_col(day);
				col_count = col_counts[day];
			}
		}
	}
	
	return col_num - 1;
}

function process_time_blocks(blocks)
{
	var one_fifty = all_blocks["one-fifty"] = [];
	var one_eighty = all_blocks["one-eighty"] = [];

	var two_fifty = all_blocks["two-fifty"] = [];
	var two_eighty = all_blocks["two-eighty"] = [];

	var three_fifty = all_blocks["three-fifty"] = [];
	var three_eighty = all_blocks["three-eighty"] = [];

	$.each(blocks, function(i, block){
		var new_block = {};
		new_block["id"] = block["id"];
		new_block["days"] = parse_days(block["days"]);
		new_block["offset"] = get_vertical_offset(block["starttm"]);
		new_block["start"] = block["starttm"];
		new_block["etime"] = block;
		new_block["length"] = block["length"];

		if (new_block["days"].length == 1)
		{
			if (block["length"] == 50)
				one_fifty.push(new_block);
			else if (block["length"] == 80)
				one_eighty.push(new_block);
		}
		else if (new_block["days"].length == 2)
		{
			if (block["length"] == 50)
				two_fifty.push(new_block);
			else if (block["length"] == 80)
				two_eighty.push(new_block);
		}
		else if (new_block["days"].length == 3)
		{
			if (block["length"] == 50)
				three_fifty.push(new_block);
			else if (block["length"] == 80)
				three_eighty.push(new_block);
		}
	});
}

function update_scheduled_class_draggables(sched_classes)
{
	sched_classes.draggable({
		scroll: false,
		stack: '#sched-container',
		cursorAt: { bottom: 0, left: 20 },
	  	helper: function( event ) 
	  	{
		return $( "<i class='fa fa-plus-circle' id='drag-helper'></i>" );
		},
		start: function(event, ui)
		{
			var class_data = get_class_info($(this));
			//var time_id = $(this).data('time');
			//var class_id = $(this).data('id');
			var old_blocks = get_class(class_data['class_id']);

			var dragged_block = $(this);
			var length = dragged_block.data('length');
			var days = parse_days(dragged_block.data('days'));
			var drop_height = (length / 5) * five_min_height;
			//var class_html = dragged_block.html();

			setup_drop_zones(length, days.length);
			
			$(".drop-zone").droppable({
				hoverClass: "time-block-hover",
				activeClass: "time-block-active",
				drop: function(event, ui)
				{
					// Hide the old blocks
					var new_time_id = $(this).data('time');
					if (new_time_id == class_data['time_id']) {
						$('.drop-zone').remove();
						return;
					}
					
					old_blocks.hide();
					
					var new_blocks = update_drop_zone(new_time_id);
					update_class_info(new_blocks, class_data);
					
					//~ $('.class-name-container').each(function() {
						//~ var course = $(this);
						//~ var div = course.closest('div.scheduled-class');
						//~ var p = div.height();
						//~ p = (p - course.outerHeight(true)) / 4;
						//~ if(p >= 7) div.css("padding-top", p + 'px');
					//~ });
					
					var edit_class_data = Object.create(null);
					edit_class_data['sched_id'] = sched_id;
					edit_class_data['mode'] = 'edit-class';
					edit_class_data['class_id'] = class_data['class_id'];
					edit_class_data['time_id'] = new_time_id;

					$.ajax({
						url: $('#edit-class-panel').data('url'),
						type: 'post',
						data: edit_class_data,
						beforeSend: function() {
							$('#checking-sched').show();
							$('#sched-bad').hide();
							$('#sched-ok').hide();
						},
						success: function(data, textStatus, jqXHR) {
							//$('#checking-sched').hide();
							//$('#sched-ok').show();
							var json_data = JSON.parse(data);
							
							update_column_matrix(old_blocks, "empty");
							update_column_matrix(new_blocks, "busy");
							
							old_blocks.remove();
							new_blocks.removeClass('new-block');
							
							update_scheduled_class_draggables(new_blocks);
							
							handle_class_conflicts(json_data);
						},
						error: function(jqXHR, textStatus, errorThrown) {
							$('#checking-sched').hide();
							$('#sched-bad').show();
							console.log(JSON.stringify(jqXHR));
							new_blocks.remove();
							old_blocks.show();
						}
					});
				},
				over: function(event, ui)
				{
					var time_id = $(this).data('time');
					$(".drop-zone[data-time=" + time_id + "]").addClass('time-block-hover');
				},
				out: function(event, ui) {
					var time_id = $(this).data('time');
					$(".drop-zone[data-time=" + time_id + "]").removeClass('time-block-hover');
				}
			});

			// Setup the trash drop zone
			$('#trash-img').droppable({
				hoverClass: "trash-hover",
				drop: function(event, ui) {
					var confirmed = confirm("Are you sure you want to delete this class from the schedule?");
					if(confirmed)
					{
						// TODO: update column matrix
						// TODO: send ajax to delete class
						old_blocks.hide();

						$.ajax({
							url: $('#edit-class-panel').data('url'),
							type: "POST",
							data: {sched_id: sched_id, mode: 'remove-class', id:class_data['class_id']},
							beforeSend: function() {
								$('#sched-ok').hide();
								$('#sched-bad').hide();
								$('#checking-sched').show();
							},
							success: function(data, textStatus, jqXHR) {
								update_column_matrix(old_blocks, "empty");
								delete course_list[old_blocks.first().text().trim()];
								refresh_autocomplete();
								old_blocks.remove();
								
								//$('#checking-sched').hide();
								var json_data = JSON.parse(data);
								
								handle_class_conflicts(json_data);
								//~ $('#checking-sched').hide();
								//~ if (json_data['wasFailure'] === 'false')
								//~ {
									//~ //$('#sched-bad').show();
									//~ //$('#conflict-section').show();
									//~ $('#conflict-section').hide();
									//~ $('#sched-ok').show();
								//~ }
								//~ else
								//~ {
									//~ $('#checking-sched').hide();
									//~ $('#sched-ok').show();
									//~ $('#conflict-section').hide();
								//~ }
							},
							error: function(jqXHR, textStatus, errorThrown) {
								/*
								$('#checking-sched').hide();
								$('#sched-bad').show();
								*/
								console.log(JSON.stringify(jqXHR));
								old_blocks.show();
								$('#checking-sched').hide();
								$('#conflict-section').hide();
								$('#sched-ok').show();
							}
						});
					}
				}
			});

			// TODO: set up other drop zones
		},
		stop: function(event, ui)
		{
			$('.drop-zone').remove();
		},
		revert: 'invalid'
	});
}

function setup_drop_zones(length, days_count)
{
	//~ var nums = ['zero', 'one', 'two', 'three', 'four', 'five'];
	//~ var key = '' + nums[days_count] + '-' + (length == 50 ? 'fifty' : 'eighty');
	//~ $.each(all_blocks[key], function(i, block){
	var blocks = all_blocks[days_count][length];
	if (blocks === undefined) return;
	$.each(blocks, function(i, block){
		$.each(block["days"], function(j, day) {
			var html_block = "<div class='drop-zone'";
			html_block += " data-time='" + block["id"] + "' ";
			var ddd = day.substring(1,4); // three char day abbreviation

			// Set offsets of the dropzone
			var left = 0;
			var top = 0;
			top += (block["offset"] * five_min_height);
			var horiz = get_horizontal_offset(block["offset"], block["length"], ddd, false);
			
			if (horiz == -1)
			{
				console.log("Horizontal failed");
				return;
			}
			left += horiz * time_block_w;

			html_block += " style='left: " + left + "px; top: " + top + "px; position: absolute' ";
			html_block += "data-col_index='" + (horiz + 1) + "' data-start='" + block["start"];
			html_block += "' data-length='" + block["length"] + "'";
			html_block += " data-days='" + block["etime"]["days"] + "'";
			html_block += " data-ddd='" + ddd + "'";
			html_block += "></div>"; // TODO: figure out tool tips
			$(day).append(html_block);
		});
	});

	$('.drop-zone').css({
		width: time_block_w +'px',
		height: ((length / 5) * five_min_height) + 'px'
	});
}

function get_popover_content(params)
{
	var days = "";
	var days_arr = [];
	
	$.each(params["days"].split('|'), function(i, day) {
		switch(day) {
			case "1":
				days_arr.push('M');
				break;
			case "2":
				days_arr.push('T');
				break;
			case "3":
				days_arr.push('W');
				break;
			case "4":
				days_arr.push('Th');
				break;
			case "5":
				days_arr.push('F');
				break;
		}
	});
	days = days_arr.join(', ');

	var times = "";



	times = "time placeholder";
	var start_time = parse_time(params['start_time']);
	var length = params["length"];

	var html = "<div style='text-align:center'><h4>New Class</h4></div>";
	html += "<b>Days: </b><em>"+ days +"</em><br>";
	html += "<b>Time: </b><em>"+ start_time +"</em><br>";
	html += "<b>Duration: </b><em>"+ length +" min</em><br><br>";
	html += '<div><form id="new-class-form" style="display: inline">';
	html += "<small><b>Class Name:</b></small><br>";
	html += "<input type='text' class='form-control' name='class_name' placeholder='Class Name' required>";
	
	html += "<small><b>Max Enrollments:</b></small><br/>";
	html += "<input type='text' class='form-control' name='enroll' placeholder='Class Enrollment' required>";
	
	html += "<small><b>Room groups:</b></small><br/>";
	html += "<select class='form-control' name='room_group'>";
	html += "<option selected>All</option>";
	$.each(room_groups, function(grp_id, grp) {
		html += '<option value="' + grp_id + '">' + grp.name + '</option>';
	});
	html += "</select>";
	
	html += "<small><b>Room:</b></small><br/>";
	html += "<select class='form-control' name='room'>";
	$.each(rooms, function(i, rm) {
		html += '<option value="' + rm['id'] + '">' + rm['name'] + '</option>';
	});
	html += "</select>";
	
	html += "<small><b>Professor:</b></small><br/>";
	html += "<select class='form-control' name='prof'>";
	$.each(professors, function(i, prof) {
		var id = prof['id'];
		var prof_name = prof['name'];
		html += '<option value="' + id + '">' + prof_name + '</option>';
	});
	html += "</select>";
	html += "<button id='new-class-btn' class='btn btn-primary'><i class='fa fa-save'></i> Save</button>";
	html += "</form><button class='btn btn-default' id='cancel-add-class'><i class='fa fa-times'></i> Cancel</button></div>";

	return html;
}

function parse_time(time_string)
{
	time_string = "" + time_string;
	var hr_str = time_string.substring(0,2);
	var min_str = time_string.substring(2);
	var hr = parseInt(hr_str);
	var min = parseInt(min_str);

	var time = "";
	var suffix = " AM";

	if (hr > 12)
	{
		time += (hr - 12) + ":";
		suffix = " PM";
	}
	else
		time += hr + ":";

	time += min_str + suffix;

	return time;
}


function load_schedule(data)
{
	var json_data = JSON.parse(data);
	console.log(json_data);
	var events = json_data['events'];
	$.each(events, function(i, ev) {
		//if (ev['etime']['length'] != 50 && ev['etime']['length'] != 80) return;
		var days = parse_days(ev['etime']['days']);
		$.each(days, function(i, day) {
			var ddd = day.substring(1, 4);
			var constraints = ev['constraints'];
			if (constraints == null) constraints = [];
			var el = jQuery('<div></div>', {
				text: ev['name'],
				'class': 'scheduled-class id-' + ev['id'],
				'data-id': ev['id'],
				'data-time': ev['etime_id'],
				'data-days': ev['etime']['days'],
				'data-ddd': ddd,
				'data-start': ev['etime']['starttm'],
				'data-length': ev['etime']['length'],
				'data-room_id': (ev['room_id'] != null ? ev['room_id'] : -1),
				'data-grp_id': (ev['room_group_id'] != null ? ev['room_group_id'] : -1),
				'data-enroll': ev['enroll_cap'],
				'data-prof_id': ev['professor']
			});
			el.data('constraints', constraints);
			el.css('height', (el.data('length') / 5) * five_min_height);
			$(day).append(el);
		});
	});
	
	// Move the staged classes to their appropriate place in the grid
	$('.scheduled-class').each(function() {
		var course = $(this);
		var start = course.data('start');
		var day = course.data('ddd');
		var length = course.data('length');
		var offsets = compute_offsets(start, day, length);

		var course_name = course.text().trim();

		//if (course_list.indexOf(course_name) < 0)
		//	course_list.push(course_name);
		course_list[course_name] = course.data('id');

		//console.log("{left: " + offsets["left"] + ", top: " + offsets["top"]);

		course.css({
			left: offsets["left"] + "px",
			top: offsets["top"] + "px"
		});

		course.data('col_index', (offsets["left"]/time_block_w) + 1);

		//$('#' + day + '-col').append(course);
		
		update_column_matrix(course, "busy");
	});

	update_scheduled_class_draggables($('.scheduled-class'));

	//course_list.sort();
	
	refresh_autocomplete();
	resize_all();

	console.log('Total columns: ' + total_cols());
}


function compute_offsets(start, day, length)
{
	var left = 0;//$(day).offset()["left"];
	var top = 0;//$(day).offset()["top"];
	var vert = get_vertical_offset(start);
	top += (vert * five_min_height);
	var horiz = get_horizontal_offset(vert, length, day, true);

	if (horiz == -1)
	{
		console.log("Horizontal failed");
		horiz = 6;
	}
	left += horiz * time_block_w;

	return {
		left: left,
		top: top
	};
}

// blocks, mode
function update_column_matrix(blocks, mode)
{
	blocks.each(function() {
		var col = $(this).data('ddd') + $(this).data('col_index');
		
		var indices = [];
		var v_offset = get_vertical_offset($(this).attr('data-start'));
		var b_length = $(this).data('length')/5;
		for (i = 0; i < b_length; i++) indices.push(v_offset + i);
		
		$.each(indices, function(i, index) {
			day_columns[col][index] = mode;
		});
	});
}

function add_matrix_col(day)
{
	//var w = $('#outer-container').width();
	//$('#outer-container').css('width', (w + time_block_w + 20) + 'px');
	var index = day + (col_counts[day] + 1);
	day_columns[index] = [];

	if (typeof day_columns[index] === 'undefined')
	{
		for (var j = 0; j < 144; j++)
			day_columns[index].push("empty");
	}

	var num_cols = (col_counts[day] + 1);


	switch(day)
	{
		case 'mon':
		case 'tue':
		case 'wed':
		case 'thu':
		case 'fri':
			$('#' + day + '-col').css('width', (num_cols * (time_block_w + 2)) + 'px');
			break;
	}

	col_counts[day] = num_cols;
	$('#outer-container').css('width', (total_cols() * (time_block_w + 2) + 60) + 'px');
}

function edit_class(course, success, fail)
{

}

function refresh_autocomplete()
{
	if ($('#class-search').autocomplete('instance') !== undefined)
		$('#class-search').autocomplete('destroy');

	$('#class-search').autocomplete({
		source: Object.getOwnPropertyNames(course_list).sort(),
		open: function(event, ui) {
			$('#class-search').keydown(function(e) {
				var value = $(this).val();
				
				if (value == '')
				{
					$('div.scheduled-class').css({
						backgroundColor: '#0099FF', 
						opacity: '1', 
						boxShadow: ''
					});
				}
				else
				{
					var valid_name = false;

					$('.scheduled-class').each(function() {
						if ($(this).text().trim() == value)
						{
							valid_name = true;
							return false;
						}
					});
					
					if (valid_name)
					{
						$('.scheduled-class').each(function() {
							if ($(this).text().trim() == value)
							{
								$(this).css({
									opacity: 1,
									backgroundColor: '#4D944D',
									boxShadow: '2px 2px 4px #363636, 0 0 6px #363636'
								});
							}
							else
								$(this).css({
									opacity: 0.2,
									backgroundColor: '#0099FF',
									boxShadow: ''
								});
						});
					}
					else
					{
						$('div.scheduled-class').css({
							backgroundColor: '#0099FF', 
							opacity: '1', 
							boxShadow: ''
						});		
					}
				}
			});

		},
		select: function(event, ui) 
		{

			var value = ui.item.label;
			
			if (value == '')
			{
				$('div.scheduled-class').css({
					backgroundColor: '#0099FF', 
					opacity: '1', 
					boxShadow: ''
				});
			}
			else
			{
				$('.scheduled-class').each(function() {
					if ($(this).text().trim() == value)
					{
						$(this).css({
							opacity: 1,
							backgroundColor: '#4D944D',
							boxShadow: '2px 2px 4px #363636, 0 0 6px #363636'
						});
					}
					else
					{
						$(this).css({
							opacity: 0.2,
							backgroundColor: '#0099FF',
							boxShadow: ''
						});
					}
				});
			}
		}
	});


	$('#class-search-container').append($('ul.ui-autocomplete').first());
	$('.ui-helper-hidden-accessible').addClass('hidden');
}

function initialize_column_matrix()
{
	day_columns["mon1"] = [];
	day_columns["tue1"] = [];
	day_columns["wed1"] = [];
	day_columns["thu1"] = [];
	day_columns["fri1"] = [];
	day_columns["mon2"] = [];
	day_columns["tue2"] = [];
	day_columns["wed2"] = [];
	day_columns["thu2"] = [];
	day_columns["fri2"] = [];
	day_columns["mon3"] = [];
	day_columns["tue3"] = [];
	day_columns["wed3"] = [];
	day_columns["thu3"] = [];
	day_columns["fri3"] = [];
	day_columns["mon4"] = [];
	day_columns["tue4"] = [];
	day_columns["wed4"] = [];
	day_columns["thu4"] = [];
	day_columns["fri4"] = [];
	day_columns["mon5"] = [];
	day_columns["tue5"] = [];
	day_columns["wed5"] = [];
	day_columns["thu5"] = [];
	day_columns["fri5"] = [];
	day_columns["mon6"] = [];
	day_columns["tue6"] = [];
	day_columns["wed6"] = [];
	day_columns["thu6"] = [];
	day_columns["fri6"] = [];
	day_columns["mon7"] = [];
	day_columns["tue7"] = [];
	day_columns["wed7"] = [];
	day_columns["thu7"] = [];
	day_columns["fri7"] = [];
	day_columns["mon8"] = [];
	day_columns["tue8"] = [];
	day_columns["wed8"] = [];
	day_columns["thu8"] = [];
	day_columns["fri8"] = [];
	day_columns["mon9"] = [];
	day_columns["tue9"] = [];
	day_columns["wed9"] = [];
	day_columns["thu9"] = [];
	day_columns["fri9"] = [];
	day_columns["mon10"] = [];
	day_columns["tue10"] = [];
	day_columns["wed10"] = [];
	day_columns["thu10"] = [];
	day_columns["fri10"] = [];

	col_counts["mon"] = 7;
	col_counts["tue"] = 7;
	col_counts["wed"] = 7;
	col_counts["thu"] = 7;
	col_counts["fri"] = 7;

	for (var j = 0; j < 144; j++)
		day_columns["mon1"].push("empty");
	for (var j = 0; j < 144; j++)
		day_columns["tue1"].push("empty");
	for (var j = 0; j < 144; j++)
		day_columns["wed1"].push("empty");
	for (var j = 0; j < 144; j++)
		day_columns["thu1"].push("empty");
	for (var j = 0; j < 144; j++)
		day_columns["fri1"].push("empty");
	
	for (var j = 0; j < 144; j++)
		day_columns["mon2"].push("empty");
	for (var j = 0; j < 144; j++)
		day_columns["tue2"].push("empty");
	for (var j = 0; j < 144; j++)
		day_columns["wed2"].push("empty");
	for (var j = 0; j < 144; j++)
		day_columns["thu2"].push("empty");
	for (var j = 0; j < 144; j++)
		day_columns["fri2"].push("empty");
	
	for (var j = 0; j < 144; j++)
		day_columns["mon3"].push("empty");
	for (var j = 0; j < 144; j++)
		day_columns["tue3"].push("empty");
	for (var j = 0; j < 144; j++)
		day_columns["wed3"].push("empty");
	for (var j = 0; j < 144; j++)
		day_columns["thu3"].push("empty");
	for (var j = 0; j < 144; j++)
		day_columns["fri3"].push("empty");
	
	for (var j = 0; j < 144; j++)
		day_columns["mon4"].push("empty");
	for (var j = 0; j < 144; j++)
		day_columns["tue4"].push("empty");
	for (var j = 0; j < 144; j++)
		day_columns["wed4"].push("empty");
	for (var j = 0; j < 144; j++)
		day_columns["thu4"].push("empty");
	for (var j = 0; j < 144; j++)
		day_columns["fri4"].push("empty");
	
	for (var j = 0; j < 144; j++)
		day_columns["mon5"].push("empty");
	for (var j = 0; j < 144; j++)
		day_columns["tue5"].push("empty");
	for (var j = 0; j < 144; j++)
		day_columns["wed5"].push("empty");
	for (var j = 0; j < 144; j++)
		day_columns["thu5"].push("empty");
	for (var j = 0; j < 144; j++)
		day_columns["fri5"].push("empty");
	
	for (var j = 0; j < 144; j++)
		day_columns["mon6"].push("empty");
	for (var j = 0; j < 144; j++)
		day_columns["tue6"].push("empty");
	for (var j = 0; j < 144; j++)
		day_columns["wed6"].push("empty");
	for (var j = 0; j < 144; j++)
		day_columns["thu6"].push("empty");
	for (var j = 0; j < 144; j++)
		day_columns["fri6"].push("empty");

	for (var j = 0; j < 144; j++)
		day_columns["mon7"].push("empty");
	for (var j = 0; j < 144; j++)
		day_columns["tue7"].push("empty");
	for (var j = 0; j < 144; j++)
		day_columns["wed7"].push("empty");
	for (var j = 0; j < 144; j++)
		day_columns["thu7"].push("empty");
	for (var j = 0; j < 144; j++)
		day_columns["fri7"].push("empty");
	
	for (var j = 0; j < 144; j++)
		day_columns["mon8"].push("empty");
	for (var j = 0; j < 144; j++)
		day_columns["tue8"].push("empty");
	for (var j = 0; j < 144; j++)
		day_columns["wed8"].push("empty");
	for (var j = 0; j < 144; j++)
		day_columns["thu8"].push("empty");
	for (var j = 0; j < 144; j++)
		day_columns["fri8"].push("empty");
	
	for (var j = 0; j < 144; j++)
		day_columns["mon9"].push("empty");
	for (var j = 0; j < 144; j++)
		day_columns["tue9"].push("empty");
	for (var j = 0; j < 144; j++)
		day_columns["wed9"].push("empty");
	for (var j = 0; j < 144; j++)
		day_columns["thu9"].push("empty");
	for (var j = 0; j < 144; j++)
		day_columns["fri9"].push("empty");
	
	for (var j = 0; j < 144; j++)
		day_columns["mon10"].push("empty");
	for (var j = 0; j < 144; j++)
		day_columns["tue10"].push("empty");
	for (var j = 0; j < 144; j++)
		day_columns["wed10"].push("empty");
	for (var j = 0; j < 144; j++)
		day_columns["thu10"].push("empty");
	for (var j = 0; j < 144; j++)
		day_columns["fri10"].push("empty");
}

function total_cols()
{
	var count = 0;

	count += col_counts['mon'];
	count += col_counts['tue'];
	count += col_counts['wed'];
	count += col_counts['thu'];
	count += col_counts['fri'];

	return count;
}

function get_class(class_id) {
	return $('.scheduled-class').filter(function (index) {
		return $(this).data('id') == class_id;
	});
}

function get_class_info(block) {
	var data = {
		class_id: block.data('id'),
		class_name: block.first().text().trim(),
		time_id: block.data('time'),
		enroll: block.data('enroll'),
		prof_id: block.data('prof_id'),
		room_id: block.data('room_id'),
		grp_id: block.data('grp_id'),
		constraints: block.data('constraints')
	};
	return data;
}

function update_class_info(new_blocks, data) {
	new_blocks.data('id', data['class_id']);
	new_blocks.addClass('id-' + data['class_id']);
	new_blocks.html(data['class_name']);
	
	if (data['newTime'] != undefined) new_blocks.data('time', data['time_id']);
	
	new_blocks.data('enroll', data['enroll']);
	new_blocks.data('prof_id', data['prof_id']);
	new_blocks.data('room_id', data['room_id']);
	new_blocks.data('grp_id', data['grp_id']);
	var constraints = data['constraints'];
	if (typeof constraints == 'string') constraints = JSON.parse(constraints);
	new_blocks.data('constraints', constraints);
}

function update_drop_zone(time_id) {
	$('.drop-zone').droppable("destroy");
	
	if (time_id != undefined) {
		var blocks = $(".drop-zone[data-time=" + time_id + "]");
		blocks.removeClass('time-block-hover time-block-active drop-zone ui-droppable');
		blocks.addClass('scheduled-class new-block');
	}
	
	$('.drop-zone').remove();
	
	return blocks;
}

function fetch_schedule(sched_id) {
	var url = $('#sched-name').data('url');
	$.ajax({
		url: url,
		type: 'post',
		data: {sched_id: sched_id},
		success: function(data, textStatus, jqXHR) {
			load_schedule(data);
		}
	});
}

function add_constraint_row(key, value) {
	var constraint_html = '<tr class="constraint-row">';
	constraint_html += '<td><select class="form-control" name="constraint-key">';
	constraint_html += '<option value="" selected></option>';
	constraint_html += '<option value="<">Must be Before</option>';
	constraint_html += '<option value=">">Must be After</option>';
	constraint_html += '<option value="=">Meeting with</option>';
	constraint_html += '</select></td>';
	constraint_html += '<td><select class="form-control" name="constraint-val">';
	constraint_html += '<option value="" selected></option>';
	$.each(course_list, function(crs_name, crs_id) {
		constraint_html += '<option value="' + crs_id + '">' + crs_name + '</option>';
	});
	constraint_html += '</select></td>';
	constraint_html += '<td class="constraint-del fa fa-trash"></td></tr>';
	
	var constraint_el = jQuery(constraint_html);
	
	constraint_el.find('.form-control[name="constraint-key"]').val(key);
	constraint_el.find('.form-control[name="constraint-val"]').val(value);
	$('#constraint-table').append(constraint_el);
}

function handle_class_conflicts(json_data) {
	$('#checking-sched').hide();
	console.log(json_data);
	if (json_data['error'] != undefined || json_data['Error'] != undefined) {
		$('#sched-ok').hide();
		$('#sched-bad').show();
		return;
	}
	
	if (json_data['wasFailure'] == true) {
		var conflicts = Object.create(null);
		var count = 0;
		
		if (json_data['EVENTS'] == undefined) return;
		
		$.each(json_data['EVENTS'], function(i, ev) {
			if (ev['conflictsWith'] == undefined) return;
			$.each(ev['conflictsWith'], function(i, other_id) {
				var a, b;
				if (ev['id'] < other_id) {
					a = ev['id'];
					b = other_id;
				}else {
					a = other_id;
					b = ev['id'];
				}
				if (conflicts[a] == undefined) conflicts[a] = Object.create(null);
				conflicts[a][b] = true;
			});
		});
		
		var el = $('#conflict-list');
		el.children('div').remove();
		var html = '';
		$.each(conflicts, function(id, list) {
			var blk1 = $('.id-' + id);
			
			// Classes with length different than 50/80 are omitted
			if (blk1.length == 0) return;
			var e1 = blk1.first().text().trim();
			$.each(list, function(other, _) {
				var blk2 = $('.id-' + other);
				if (blk2.length == 0) return;
				
				count += 1;
				var e2 = blk2.first().text().trim();
				html += '<div class="conflict-row" data-id1="' + blk1.data('id') + '" ';
				html += 'data-id2="' + blk2.data('id') + '"><b>';
				html += e1 + '</b> conflicts with <b>' + e2 + '</b></div>';
			});
		});
		el.html(html);
		
		if (count > 0) {
			$('#sched-ok').hide();
			$('#sched-bad').show();
			$('#conflict-section').show();
		} else {
			$('#conflict-section').hide();
			$('#sched-bad').hide();
			$('#sched-ok').show();
		}
	} else {
		$('#conflict-section').hide();
		$('#sched-bad').hide();
		$('#sched-ok').show();
	}
}

function fetch_time_blocks() {
	var url = $('#toolbox').data('url');
	
	$.ajax({
		url: url,
		type: 'post',
		success: function(data, textStatus, jqXHR) {
			var json_data = JSON.parse(data);
			$.each(json_data, function(i, block) {
				if (block['starttm'] <= '0730') return;
				var new_block = {};
				new_block["id"] = block["id"];
				new_block["days"] = parse_days(block["days"]);
				new_block["offset"] = get_vertical_offset(block["starttm"]);
				new_block["start"] = block["starttm"];
				new_block["etime"] = block;
				new_block["length"] = block["length"];
				
				var days_count = new_block["days"].length;
				var length = block['length'];
				var list = all_blocks[days_count];
				if (list == undefined) {
					list = all_blocks[days_count] = Object.create(null);
				}
				if (list[length] == undefined) {
					list[length] = [];
				}
				list = list[length];

				list.push(new_block);
			});
			
			nums = ['zero', 'one', 'two', 'three'];
			for (var i = 1; i <= 3; i++) {
				var table = $('#collapse-' + nums[i]).find('table');
				table.children().remove();
				var list = [];
				$.each(all_blocks[i], function(length, _) {
					list.push(length);
				});
				list.sort(function(a,b) {
					a = parseInt(a);
					b = parseInt(b);
					return a>b ? 1 : (a<b ? -1 : 0);
				});
				var head = '<tr>', content = '<tr>';
				$.each(list, function(_, length) {
					head += '<td>' + length + ' min:</td>';
					content += '<td><div class="draggable" data-length="' + length + '" ';
					content += 'data-days="' + i + '" ';
					content += 'style="background-color: orange; width: 40px; ';
					content += 'height: ' + (length/5) * five_min_height + 'px;"></div></td>';
				});
				head += '</tr>';
				content += '</tr>';
				table.append(head + content);
			}
			
			$('.draggable').draggable({
				//cursorAt: { bottom: 25, left: 0 },
				//cursor: "url(" + cursor_img + "), auto",
				cursorAt: { bottom: 0, left: 20 },
				helper: function( event ) 
				{
					return $( "<i class='fa fa-plus-circle' id='drag-helper'></i>" );
				},
				start: function(event, ui)
				{
					var dragged_block = $(this);
					
					var length = dragged_block.data('length');
					var days_count = dragged_block.data('days');
					setup_drop_zones(length, days_count);

					$('#outer-container').css('width', (total_cols() * (time_block_w + 2)) + 'px');

					$('#sched-container').css('opacity', 0.5);

					$('.drop-zone').droppable({
						hoverClass: "time-block-hover",
						activeClass: "time-block-active",
						drop: function(event, ui) {
							if ($('#new-class-form').length > 0) {
								update_drop_zone();
								return;
							}
							
							var time_id = $(this).data('time');
							var blocks = update_drop_zone(time_id);
							
							var params = [];
							params["col"] = $(this).data('col_index');
							params["days"] = '' + $(this).data('days');
							params["start"] = $(this).attr('data-start');
							params["start_time"] = params['start'];
							params["length"] = $(this).data('length');
							
							var content = get_popover_content(params);

							// Show popover
							var pos = parseFloat($(this).css('top')) + $(this).height()/2;
							var el = $(this);

							$(this).popover({
								trigger: "manual",
								html: true,
								content: content
							}).on('shown.bs.popover', function() {
								if(parseInt($('.popover').css('top')) < 0)
								{
									$('.popover').css('top', 0);
									$('.arrow').css('top', pos + 'px');
								}
								
								$('#new-class-form').data('block', blocks);
								
								$('#cancel-add-class').click(function() {
									el.popover('destroy');
									blocks.remove();
								});

							}).popover('show');
						},
						over: function(event, ui) {
							var time_id = $(this).data('time');
							$(".drop-zone[data-time=" + time_id + "]").addClass('time-block-hover');
						},
						out: function(event, ui) {
							var time_id = $(this).data('time');
							$(".drop-zone[data-time=" + time_id + "]").removeClass('time-block-hover');
						}
					});
				},
				stop: function(event, ui)
				{
					$('.drop-zone').remove();
					$('#sched-container').css('opacity', 1);
				},
				revert: 'invalid'
			});
		}
	});
}
