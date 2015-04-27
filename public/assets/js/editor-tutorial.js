var tour_ended = false;

$(function() {

	$('#center-slide-anchor').css({
		position: 'absolute',

		top: $(window).height() / 2 + 'px',
		left: $(window).width() / 2 + 'px'
	});

	create_tutorial();
	
	$('#view-tut-link').click(function(e) {
		e.preventDefault();

		if (tour_ended)
			create_tutorial();

		$.tutorialize.cleanRemember('editor');
		$.tutorialize.start('editor');
	});

//	$.tutorialize.start('editor');

});

function create_tutorial()
{
	var oMode = 'focus';
	var oOpacity = 0.6;
	var oPadding = 10;

	$.tutorialize({
		overlayMode: oMode,
		overlayOpacity: oOpacity,
		overlayPadding: oPadding,
		arrowPath: '../arrows/arrow-blue.png',
		minWidthCSS: '300px',
		minWidth: '300px',
		width: 300,
		fontSize: '15px',
		labelNext: 'Next <i class="fa fa-chevron-right"></i>',
		labelPrevious: '<i class="fa fa-chevron-left"></i> Previous',
		rememberOnceOnly: true,
		remember: true,
		keyboardNavigation: false,
		slides: [
			{
				title: '<h2>Welcome to the Schedule Editor</h2>',
				content: '<p>Click "Start" to begin your guided tour.</p><p><small style="font-size: 11px">Press the <b style="font-size: 12px">ESC</b> key at any time to quit the tour.</small></p>',
				selector: '#center-slide-anchor',
				overlayMode: 'all',
				position: 'center-center',
				onSlide: function() {
					$('#custom_navbar').zIndex(1);
				}
			},
			{
				selector: '#toggle-toolbox',
				position: 'right-center',
				title: '<h2>Open the Toolbox</h2>',
				content: 'Clicking this tab will display your toolbox.  From there you can add classes, search the schedule, or view any scheduling conflicts.',
				overlayMode: 'all',
				arrowOffset: 20,
				onNext: function() {
					open_toolbox_transition();
				}
			},
			{
				selector: '#toolbox > h3',
				position: 'right-center',
				title: '<h2>Adding Classes</h2>',
				content: 'As most CS classes are either 50 or 80 minutes long, we have included short cuts for adding classes with those lengths. If you wish to add a class with a different length, use the \'Custom Time Block\' option.',
				overlayMode: 'all',
				arrowOffset: -15,
				onNext: function() {
					open_sched_search();
				},
				onSlide: function() {
					$('.tutorialize-slide-overlay').zIndex(9);
				}
			},
			{
				selector: '#sched-search-h3',
				position: 'right-center',
				title: '<h2>Searching the Schedule</h2>',
				content: 'You can locate a class on the schedule by searching for it here.  You can quickly access the search feature by using the keyboard shortcut <b>CTRL + f</b>.',
				overlayMode: 'all',
				arrowOffset: -15,
				onSlide: function() {
					$('.tutorialize-slide-overlay').zIndex(9);
				},
				onNext: function() {
					close_toolbox_transition();
				},
			},
			{
				selector: '.scheduled-class',
				position: 'right-center',
				title: '<h2>Editing Classes</h2>',
				content: 'Clicking on a scheduled class will open a panel for editing that class.',
				overlayMode: 'focus',
				arrowOffset: 15,
				onSlide: function() {
					$('.tutorialize-slide-overlay').zIndex(198);
				},
				onNext: function() {
					open_right_panel();
				}
			},
			{
				selector: '#class-info-section',
				positon: 'center-left',
				title: '<h2>Class Info</h2>',
				content: 'Here you can edit any of the properties of this class, including name, room, professor, etc.',
				overlayMode: 'focus',
				onSlide: function() {
					var slide = $('.tutorialize-slide').first();
					var left = parseInt(slide.css('left'));
					slide.css('left', (left - 370) + 'px');
				}
			},
			{
				selector: '#constraints-section',
				position: 'left-center',
				title: '<h2>Constraints</h2>',
				content: 'Here you can add, remove, and modify the constraints that are associated with this class.',
				overlayMode: 'focus',
				arrowOffset: 15
			}
		],
		onStart: function(index, data, dom) {
			$('body').keydown(function(e){
				var index = $.tutorialize.getCurrentIndex('editor');

				switch(e.which)
				{
					case 27:
						$.tutorialize.stop('editor');
						break;
					case 39:
						e.preventDefault();
						switch(index)
						{
							case 1:
								open_toolbox_transition();
								break;
							case 2:
								open_sched_search();
								break;
							case 3:
								close_toolbox_transition();
								break;
							case 4:
								open_right_panel();
								break;
							default:
								$.tutorialize.next('editor');
						}
						break;
					case 37:
						e.preventDefault();
						$.tutorialize.prev('editor');
						break;
				}
			});
		},
		onStop: function() {
			tour_ended = true;
			$('body').off('keydown');
			$('#custom_navbar').zIndex(999);
		}
	}, 'editor');
}

function open_toolbox_transition()
{
	if(!panel_is_open)
	{
		$('#custom_navbar').zIndex(999);
		$('#toggle-toolbox').click();
	}

	setTimeout(function() { 
		$('html').scrollLeft(0);
		$.tutorialize.next('editor'); 
	}, 250);
}

function close_toolbox_transition()
{
	if(panel_is_open)
	{
		$('#custom_navbar').zIndex(999);
		$('#toggle-toolbox').click();
	}

	setTimeout(function() { $.tutorialize.next('editor'); }, 220);
}

function open_sched_search()
{
	$('.panel-collapse').each(function() {
		if ($(this).hasClass('in') && ($(this).attr('id') != 'collapse-six'))
			$(this).collapse('hide');
	});

	if (!($('#collapse-six').hasClass('in')))
		$('#collapse-six').collapse('show');

	// Place focus in the text input
	$('#class-search').focus();

	$.tutorialize.next('editor');
}

function open_right_panel()
{
	$('.scheduled-class').first().click();

	setTimeout(function() { $.tutorialize.next('editor'); }, 350);	
}