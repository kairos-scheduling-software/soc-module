var five_min_height;
var time_block_w;
var all_blocks = {};
var day_columns = [];
var blocks_to_update = [];
var group_counter = 1;
var add_class_url;
var remove_class_url;
var sched_id;
var col_counts = [];
var course_list = [];
var temp_indices = [];

var rooms;
var room_groups;

$(function(){

	initialize_column_matrix();

	add_class_url = $('#hidden-data').attr('data-addurl');
	remove_class_url = $('#hidden-data').attr('data-removeurl');
	sched_id = $('#hidden-data').attr('data-schedid');
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
		var group_id = $('#new-class-btn').attr('data-group');
		var form = $(this);
		var params = form.data('params');
		var class_name = $('input[name="class_name"').val();

		$("div[data-group=" + group_id + "]").html("<span class='class-name-container'>" + class_name + "</span");
		$("div[data-group=" + group_id + "]").popover('destroy');
		
		var data = form.serializeArray();
		var url = form.attr('action');

		$.ajax({
			url:		url,
			type: 		"POST",
			data: 		data,
			beforeSend: function() {
				$('#sched-ok').hide();
				$('#sched-bad').hide();
				$('#checking-sched').show();
			},
			success: 	function(data, textStatus, jqXHR) {
				$('#checking-sched').hide();
				var json_data = JSON.parse(data);

				// Update the matrix
				$("div[data-group=" + group_id + "]").each(function() {
					var col = $(this).data('col');
					var day = $(this).closest('div.sched-day-column').data('ddd');
					var col_index = day + col;
					var indices = [];

					for (i = params["v_offset"]; i < (params["v_offset"] + params["b_length"]); i++)
						indices.push(i);

					$(this).data('col_index', col_index);
					$(this).data('indices', indices);

					update_column_matrix(col_index, indices, "busy");
				});
				if (json_data['wasFailure'])
				{
					console.log('was failure: true');
					$('#sched-bad').show();
					$('#conflict-section').show();
				}
				else
				{
					console.log('was failure: false');
					$('#sched-ok').show();
				}
			},
			error: 		function(jqXHR, textStatus, errorThrown) {
				console.log(JSON.stringify(jqXHR));
				$('#checking-sched').hide();
				$('#sched-bad').show();
			}
		});

		return true;
	});

	resize_all();
	/*
	$(window).resize(function(){
		setTimeout(function() {
			resize_all();
		}, 500);		
	});
*/
	load_schedule();

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
    		
    		if (dragged_block.hasClass('one-fifty'))
    			setup_dropzones('one-fifty', 'fifty-min-blk');
    		else if (dragged_block.hasClass('one-eighty'))
    			setup_dropzones('one-eighty', 'eighty-min-blk');
    		else if (dragged_block.hasClass('two-fifty'))
    			setup_dropzones('two-fifty', 'fifty-min-blk');
    		else if (dragged_block.hasClass('two-eighty'))
    			setup_dropzones('two-eighty', 'eighty-min-blk');
    		else if (dragged_block.hasClass('three-fifty'))
    			setup_dropzones('three-fifty', 'fifty-min-blk');
    		else if (dragged_block.hasClass('three-eighty'))
    			setup_dropzones('three-eighty', 'eighty-min-blk');

    		$('#outer-container').css('width', (total_cols() * (time_block_w + 2)) + 'px');

    		$('#sched-container').css('opacity', 0.5);

    		$('.drop-zone').droppable({
		    	hoverClass: "time-block-hover",
		    	activeClass: "time-block-active",
		    	drop: function(event, ui) {
		    		group_counter++;
		    		var group_id = $(this).attr('data-group');
		    		$('.drop-zone').droppable("destroy");
		    		$("div[data-group=" + group_id + "]").removeClass('time-block-hover time-block-active drop-zone ui-droppable');
		    		$("div[data-group=" + group_id + "]").addClass('scheduled-class not-yet-added');

		    		var params = [];
		    		params["col"] = parseInt($(this).attr('data-col'));
		    		params["day_string"] = $(this).attr('data-days');
		    		params["v_offset"] = parseInt($(this).attr('data-start'));
		    		params["time"] = parse_time($(this).data('time'));
		    		params["b_length"] = parseInt($(this).attr('data-length'));
		    		params["days"] = [];

		    		if (params["day_string"].indexOf("mon") >=0)
		    			params["days"].push('mon');
		    		if (params["day_string"].indexOf("tue") >=0)
		    			params["days"].push('tue');
		    		if (params["day_string"].indexOf("wed") >=0)
		    			params["days"].push('wed');
		    		if (params["day_string"].indexOf("thu") >=0)
		    			params["days"].push('thu');
		    		if (params["day_string"].indexOf("fri") >=0)
		    			params["days"].push('fri');

		    		var content = get_popover_content(group_id, params);
		    		$("div[data-group=" + group_id + "]").attr('data-content', content);

		    		// Show popover
		    		var pos = parseFloat($(this).css('top')) + $(this).height()/2;
		    		var el = $(this);

		    		$(this).popover({
		    			trigger: "manual",
		    			html: true
		    		}).on('shown.bs.popover', function() {
		    			if(parseInt($('.popover').css('top')) < 0)
	    				{
	    					$('.popover').css('top', 0);
	    					$('.arrow').css('top', pos + 'px');
	    				}

	    				$('#new-class-form').data('params', params);

	    				$('#cancel-add-class').click(function() {
	    					el.popover('destroy');
	    					$('.not-yet-added').remove();
	    				});

		    		}).popover('show');

		    		$("div[data-group=" + group_id + "]").html();

		    		$('.drop-zone').remove();	    		

		    		refresh_scheduled_class_draggables();
		    	},
		    	over: function(event, ui) {
		    		var group_id = $(this).attr('data-group');
		    		$("div[data-group=" + group_id + "]").each(function() {
		    			if (!$(this).hasClass('scheduled-class'))
		    				$(this).addClass('time-block-hover');
		    		});
		    		//console.log($(this).attr('data-days'));
		    		//console.log($(this).attr('data-time'));
		    	},
		    	out: function(event, ui) {
		    		var group_id = $(this).attr('data-group');
		    		$("div[data-group=" + group_id + "]").removeClass('time-block-hover');
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

    $('.trash-can').droppable({
    	hoverClass: "trash-hover"
    });

	$('#add-time-btn').click(function(e){
		var input = $('#custom-duration-input');
		var value = parseInt(input.val());

		if (value < 480)
			input.val(value + 5);
		else
			return;
	});

	$('#remove-time-btn').click(function(e) {
		var input = $('#custom-duration-input');
		var value = parseInt(input.val());

		if (value > 50)
			input.val(value - 5);
		else
			return;
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

	$('.scheduled-class').click(function() {
		console.log("Column: " + $(this).data('ddd') + $(this).data('col_index'));
		console.log("Indices: " + $(this).data('indices'));
	});
});

$(document).ready(function () {
	$('body').on('change', 'select[name="room_grp_name"]', function(e) {
		var grp_name = $('select[name="room_grp_name"] option:selected')[0].value;
		var rm_list;
		if (grp_name == 'All') {
			rm_list = rooms;
		} else {
			rm_list = room_groups[grp_name];
		}
		var html = '';
		$.each(rm_list, function(i, rm) {
			html += '<option>' + rm + '</option>';
		});
		$('select[name="room_name"] option').remove();
		$('select[name="room_name"]').append(html);
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

	var sched_height = $(window).height() 
							- $('.top-buffer').outerHeight(true) 
							- $('#sched-name').outerHeight(true) 
							- $('#sched-col-headers').outerHeight(true)
							- $('#bottom-container').outerHeight(true)
							- parseInt($('#page_footer').css('height'));

	//$('#main-container').css('width', $(window).width() + 'px');
	time_block_w = 40;//$('#main-container').width() / 35;

	$('#time-labels').css('height', sched_height + 'px');
	five_min_height = $('#inner-container').height() / 144;

	$('.fifty-min-blk').css('height', (10 * five_min_height));
	$('.eighty-min-blk').css('height', (16 * five_min_height));

	$('#left-side-bar').css('height', ($(window).height() - 40) + 'px');

	$('.class-name-container').each(function() {
		var course = $(this);
		var div = course.closest('div.scheduled-class');
		//console.log("div: " + div.attr('data-group'));
		var p = div.height();
		p = (p - course.outerHeight(true)) / 4;

		if(p >= 7)
		div.css("padding-top", p + 'px');
	});
}

function parse_days(json_days)
{
	var days = [];

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
			update = [];
			//console.log("Location " + col_index + "[" + i + "] is in use.");
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
		else
			update.push(i);
	}

	if(loading_mode)
	{
		update_column_matrix(col_index, update, "busy");
		temp_indices = update;
	}

	//console.log("Col Num: " + col_num);
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

function setup_dropzones(key, block_class)
{
	$.each(all_blocks[key], function(i, block)
	{		
		$.each(block["days"], function(j, day) {
			var html_block = "<div class='drop-zone " + block_class + " " + key + "'";
			html_block += " data-group='" + block["id"] + "-" + group_counter + "' ";
			var id = day.substring(1) + "-" + block["id"];
			var ddd = id.substring(0,3); // three char day abbreviation
			html_block += "id='" + id + "'";

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

			html_block += " style='left: " + left + "px; top: " + top + "px;' ";
			html_block += "data-col='" + (horiz + 1) + "' data-start='" + block["offset"];
			html_block += "' data-length='" + (block["length"] / 5) + "'";
			html_block += " data-days='" + block["days"] + "'";
			html_block += " data-time='" + block["etime"]["starttm"] + "'";
			html_block += " data-ddd='" + ddd + "'";
			html_block += "></div>"; // TODO: figure out tool tips
			$(day).append(html_block);
		});
	});

	switch(block_class)
	{
		case "fifty-min-blk":
			$('.fifty-min-blk').css('height', (10 * five_min_height));
			break;
		case "eighty-min-blk":
			$('.eighty-min-blk').css('height', (16 * five_min_height));
			break;
	}
}

function refresh_scheduled_class_draggables()
{
	$('.scheduled-class').draggable({
		scroll: false,
		stack: '#sched-container',
		cursorAt: { bottom: 0, left: 20 },
      	helper: function( event ) 
      	{
        	return $( "<i class='fa fa-plus-circle' id='drag-helper'></i>" );
    	},
    	start: function(event, ui)
    	{
    		var group_id = $(this).attr('data-group');
    		var id = $(this).attr('id');
    		//$("div[data-group=" + group_id + "]:not('#"+id+"')").hide();

    		var dragged_block = $(this);
    		var length = dragged_block.data('length');
    		var days = parse_days(dragged_block.data('days'));
    		var drop_height = (length / 5) * five_min_height;
    		var class_html = dragged_block.html();

    		scheduled_class_drop_zones(length, days, drop_height);
    		
    		$(".sc_drop_zone").droppable({
				hoverClass: "time-block-hover",
		    	activeClass: "time-block-active",
		    	drop: function(event, ui)
		    	{
		    		$('.sc_drop_zone').droppable("destroy");

		    		// Remove the old blocks
		    		$("div[data-group=" + group_id + "]").remove();

		    		var blk = $(this);
		    		var group = blk.data('group');

		    		$('.sc_drop_zone').each(function() {
		    			var this_block = $(this);
		    			if (this_block.data('group') == group)
		    			{
		    				this_block.removeClass('time-block-hover time-block-active drop-zone ui-droppable sc_drop_zone');
		    				this_block.addClass('scheduled-class not-yet-added');
		    				this_block.html(class_html);
		    			}
		    		});

		    		$('.class-name-container').each(function() {
						var course = $(this);
						var div = course.closest('div.scheduled-class');
						//console.log("div: " + div.attr('data-group'));
						var p = div.height();
						p = (p - course.outerHeight(true)) / 4;

						if(p >= 7)
						div.css("padding-top", p + 'px');
					});

		    		$('.sc_drop_zone').remove();
		    	},
		    	over: function(event, ui)
		    	{
		    		var group_id = $(this).attr('data-group');
		    		$("div[data-group=" + group_id + "]").each(function() {
		    			if (!$(this).hasClass('scheduled-class'))
		    				$(this).addClass('time-block-hover');
		    		});
		    	},
		    	out: function(event, ui) {
		    		var group_id = $(this).attr('data-group');
		    		$("div[data-group=" + group_id + "]").removeClass('time-block-hover');
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
		    			var id = $("div[data-group=" + group_id + "]").data('class');
		    			$("div[data-group=" + group_id + "]").remove();

		    			$.ajax({
							url:		remove_class_url,
							type: 		"POST",
							data: 		{'id':id, 'schedule':sched_id},
							dataType: JSON,
							beforeSend: function() {
								$('#sched-ok').hide();
								$('#sched-bad').hide();
								$('#checking-sched').show();
							},
							success: 	function(data, textStatus, jqXHR) {
								$('#checking-sched').hide();
								var json_data = JSON.parse(data);
								$('#checking-sched').hide();
								if (json_data['wasFailure'] === 'false')
								{
									//$('#sched-bad').show();
									//$('#conflict-section').show();
									$('#conflict-section').hide();
									$('#sched-ok').show();
								}
								else
								{
									$('#checking-sched').hide();
									$('#sched-ok').show();
									$('#conflict-section').hide();
								}				
							},
							error: 		function(jqXHR, textStatus, errorThrown) {
								/*
								console.log(JSON.stringify(jqXHR));
								$('#checking-sched').hide();
								$('#sched-bad').show();
								*/
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
    		$('.sc_drop_zone').remove();
    	},
    	revert: 'invalid'
	});
}

function scheduled_class_drop_zones(length, days, height)
{
	var nums = ['zero', 'one', 'two', 'three', 'four', 'five'];
	var key = '' + nums[days.length] + '-' + (length == 50 ? 'fifty' : 'eighty');
	$.each(all_blocks[key], function(i, block){
		$.each(block["days"], function(i, day) {
			var html_block = "<div class='sc_drop_zone'";
			html_block += " data-group='" + block["id"] + "-" + group_counter + "' ";
			var id = day.substring(1) + "-" + block["id"];
			var ddd = id.substring(0,3); // three char day abbreviation
			html_block += "id='" + id + "'";

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
			html_block += "data-col='" + (horiz + 1) + "' data-start='" + block["offset"];
			html_block += "' data-length='" + (block["length"] / 5) + "'";
			html_block += " data-days='" + block["days"] + "'";
			html_block += " data-time='" + block["etime"]["starttm"] + "'";
			html_block += " data-ddd='" + ddd + "'";
			html_block += "></div>"; // TODO: figure out tool tips
			$(day).append(html_block);
		});
	});

	$('.sc_drop_zone').css({
		width: time_block_w +'px',
		height: height +'px'
	});
}

function get_popover_content(group_id, params)
{
	var blk_id = parseInt(group_id.substring(0, group_id.indexOf('-')));
	var days = "";

	$.each(params["days"], function(i, day) {
		switch(day)
		{
			case "mon":
				if(days == "")
					days = "M";
				else
					days += ", M";
				break;
			case "tue":
				if(days == "")
					days = "T";
				else
					days += ", T";
				break;
			case "wed":
				if(days == "")
					days = "W";
				else
					days += ", W";
				break;
			case "thu":
				if(days == "")
					days = "Th";
				else
					days += ", Th";
				break;
			case "fri":
				if(days == "")
					days = "F";
				else
					days += ", F";
				break;
		}
	});

	var times = "";



	times = "time placeholder";

	var html = "<div style='text-align:center'><h4>New Class</h4></div>";
	html += "<b>Days: </b><em>"+ days +"</em><br>";
	html += "<b>Time: </b><em>"+ params["time"] +"</em><br>";
	html += "<b>Duration: </b><em>"+ (params["b_length"] * 5) +" min</em><br><br>";
	html += "<div><form action='"+add_class_url+"' id='new-class-form' style='display: inline'>";
	html += "<input type='hidden' name='block_id' value='"+blk_id+"'>";
	html += "<input type='hidden' name='sched_id' value='"+sched_id+"'>";
	html += "<small><b>Class Name:</b></small><br>";
	html += "<input type='text' class='form-control' name='class_name' placeholder='Class Name' required>";
	
	html += "<small><b>Room groups:</b></small><br/>";
	html += "<select class='form-control' name='room_grp_name'>";
	html += "<option selected>All</option>";
	$.each(room_groups, function(grp, obj) {
		html += "<option>" + grp + "</option>";
	});
	html += "</select><br/>";
	
	html += "<small><b>Room:</b></small><br/>";
	html += "<select class='form-control' name='room_name'>";
	//$.each(rooms, function(rm, grps) {
	//	html += "<option class='" + grps + "'>" + rm + "</option>";
	//});
	html += "</select><br/>";
	//html += "<input type='text' class='form-control' name='room_name' placeholder='Room' required>";
	html += "<small><b>Professor:</b></small><br>";
	html += "<input type='text' class='form-control' name='prof_name' placeholder='Professor' required>";
	html += "<button data-group='"+group_id+"' id='new-class-btn' class='btn btn-primary'><i class='fa fa-save'></i> Save</button>";
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


function load_schedule()
{
	// Move the staged classes to their appropriate place in the grid
	$('#class-staging > div.scheduled-class').each(function() {
		var course = $(this);
		var days = parse_days("" + course.data('days'));
		var start = course.data('start');
		var col = course.data('col');
		var length = course.data('length');
		var offsets = compute_offsets(start, col, length);
		var indices = temp_indices;

		var course_name = course.find('span.class-name-container').text();

		if (course_list.indexOf(course_name) < 0)
			course_list.push(course_name);

		//console.log("{left: " + offsets["left"] + ", top: " + offsets["top"]);

		course.css({
			left: offsets["left"] + "px",
			top: offsets["top"] + "px"
		});

		course.data('col_index', (offsets["left"]/time_block_w) + 1);
		course.data('indices', indices);

		$(col).append(course);
	});

	refresh_scheduled_class_draggables();

	course_list.sort();
	
	$('#class-search').autocomplete({
		source: course_list,
		open: function(event, ui) {

			$('.ui-menu-item').click(function() {
				$('#class-search').val($(this).text());
				var name = $(this).text();
				console.log('click');

				$('.class-name-container').each(function() {
					if ($(this).text() == name)
					{
						$(this).closest('div').css({
							opacity: 1,
							backgroundColor: '#4D944D',
							boxShadow: '2px 2px 4px #363636, 0 0 6px #363636'
						});
					}
					else
						$(this).closest('div').css({
							opacity: 0.2,
							backgroundColor: '#0099FF',
							boxShadow: ''
						});
				});
			});
		}
	});

	$('#class-search-container').append($('#ui-id-1'));
	$('.ui-helper-hidden-accessible').addClass('hidden');

	console.log('Total columns: ' + total_cols());
}


function compute_offsets(start, day, length)
{
	var left = 0;//$(day).offset()["left"];
	var top = 0;//$(day).offset()["top"];
	var vert = get_vertical_offset(start);
	top += (vert * five_min_height);
	var horiz = get_horizontal_offset(vert, length, day.substring(1,4), true);

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

function update_column_matrix(col, indices, mode)
{
	$.each(indices, function(i, index) {
		day_columns[col][index] = mode;
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
			$('#mon-col').css('width', (num_cols * time_block_w) + 'px');
			break;
		case 'tue':
			$('#tue-col').css('width', (num_cols * time_block_w) + 'px');
			break;
		case 'wed':
			$('#wed-col').css('width', (num_cols * time_block_w) + 'px');
			break;
		case 'thu':
			$('#thu-col').css('width', (num_cols * time_block_w) + 'px');
			break;
		case 'fri':
			$('#fri-col').css('width', (num_cols * time_block_w) + 'px');
			break;
	}

	col_counts[day] = num_cols;
	$('#outer-container').css('width', (total_cols() * (time_block_w + 2)) + 'px');
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
