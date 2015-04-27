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
				overlayMode: 'none',
				arrowOffset: 30,
				onNext: function() {
					open_toolbox_transition();
				}
			},
			{
				selector: '#toolbox > h3',
				position: 'right-center',
				title: '<h2>Adding Classes</h2>',
				content: 'As most CS classes are either 50 or 80 minutes long, we have included short cuts for adding classes with those lengths. If you wish to add a class with a different length, use the \'Custom Time Block\' option',
				overlayMode: 'none',
				arrowOffset: -15
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
						if (index == 1)
							open_toolbox_transition();
						else
							$.tutorialize.next('editor');
						break;
					case 37:
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

	setTimeout(function() { $.tutorialize.next('editor'); }, 220);
}