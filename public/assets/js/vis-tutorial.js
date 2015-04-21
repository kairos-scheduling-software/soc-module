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
		console.log('clicked!');
		if (tour_ended)
			create_tutorial();

		$.tutorialize.cleanRemember('vis-tut');
		$.tutorialize.start('vis-tut');
	});

	setTimeout(function() {
		//$.tutorialize.start('vis-tut');
	}, 5000);

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
		minWidthCSS: '300px',
		minWidth: '300px',
		width: 300,
		fontSize: '15px',
		labelNext: 'Next <i class="fa fa-chevron-right"></i>',
		labelPrevious: '<i class="fa fa-chevron-left"></i> Previous',
		rememberOnceOnly: true,
		remember: true,
		//keyboardNavigation: false,
		slides: [
			{
				title: '<h2>Welcome to the Kairos Data Tools</h2>',
				content: '<p>Click "Start" to begin your guided tour.</p><p><small style="font-size: 11px">Press the <b style="font-size: 12px">ESC</b> key at any time to quit the tour.</small></p>',
				selector: '#center-slide-anchor',
				overlayMode: 'all',
				position: 'center-center'
			},
			{
				selector: '#left-nav',
				position: 'left-center',
				title: '<h2>Visualizations</h2>',
				content: 'From this menu you can choose which data visualization you want to see.',
				arrowOffset: -15
			}
		],
		onStart: function(index, data, dom) {
			$('body').keydown(function(e){
				if(e.which == 27)
					$.tutorialize.stop('vis-tut');
			});
		},
		onStop: function() {
			tour_ended = true;
			$('body').off('keydown');
		}
	}, 'vis-tut');	
}