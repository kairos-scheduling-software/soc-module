var tour_ended = false;
var nav_shadow;

$(function() {

	$('#center-slide-anchor').css({
		position: 'absolute',

		top: $(window).height() / 2 + 'px',
		left: $(window).width() / 2 + 'px'
	});

	create_tutorial();

	nav_shadow = $('#custom_navbar').css('box-shadow');
	
	$('#view-tut-link').click(function(e) {
		e.preventDefault();
		console.log('clicked!');
		if (tour_ended)
			create_tutorial();

		$.tutorialize.cleanRemember('dash');
		$.tutorialize.start('dash');
	});

	$.tutorialize.start('dash');

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
		keyboardNavigation: false,
		slides: [
			{
				title: '<h2>Welcome to the Kairos Dashboard</h2>',
				content: '<p>The Dashboard page is the central hub of the Kairos application.  From here you can access all of the app\'s features. </p><p>Click "Start" to begin your guided tour.</p>',
				selector: '#center-slide-anchor',
				overlayMode: 'all',
				position: 'center-center'
			},
			{
				selector: '#dash-nav-link',
				position: 'bottom-left',
				title: '<h2>Dashboard</h2>',
				content: 'You can easily return to the Dashboard from anywhere by clicking this link.',
				overlayMode: 'all',
				arrowOffset: -15,
				onSlide: function() {
					setTimeout(function(){
						$('#custom_navbar').zIndex(198);
						$('tutorialize-slide-arrow').zIndex(1000);
					}, 100);
				}
			},
			{
				selector: '#data-tools-nav-link',
				position: 'bottom-center',
				title: '<h2>Data Tools</h2>',
				content: 'Kairos has 15 years worth of scheduling data pre-loaded. The Data Tools section provides powerful visualizations that help you analyze that data and make smart decisions about future schedules.',
				overlayMode: 'all',
				arrowOffset: -15,
				onSlide: function() {
					setTimeout(function(){
						$('#custom_navbar').zIndex(198);
						$('tutorialize-slide-arrow').zIndex(1000);
					}, 100);
				}
			},
			{
				selector: '#help-nav-link',
				position: 'bottom-left',
				title: '<h2>Help Link</h2>',
				content: 'Not sure what to do? Access page tutorials by clicking the help link on any page within Kairos.',
				overlayMode: 'all',
				arrowOffset: -15,
				onSlide: function() {
					setTimeout(function(){
						$('#custom_navbar').zIndex(198);
						$('tutorialize-slide-arrow').zIndex(1000);
					}, 100);
				}
			},
			{
				selector: '#manage-account-nav-link',
				position: 'bottom-center',
				title: '<h2>Account Settings</h2>',
				content: 'Clicking here will take you to your account settings page.  From there you can change your email settings, your password, and your profile picture.',
				overlayMode: 'all',
				arrowOffset: -15,
				onSlide: function() {
					setTimeout(function(){
						$('#custom_navbar').zIndex(198);
						$('tutorialize-slide-arrow').zIndex(1000);
					}, 100);
				}
			},
			{
				title: '<h2>Creating Schedules</h2>',
				content: 'You can create a new schedule from scratch, or import your schedule from a CSV file.',
				selector: '#create-sched-section',
				position: 'right-center'
			},
			{
				title: '<h2>Comparing Schedules</h2>',
				content: 'Need to see the differences between two of your saved schedules?  This tool makes it easy!',
				selector: '#comp-sched-section',
				position: 'right-center'
			},
			{
				title: '<h2>Schedule List</h2>',
				content: '<p>Your saved schedules will show up here.  You can sort this list by clicking on the column headers.</p><p>Clicking on a row will open a panel for interacting with that schedule.</p>',
				selector: '#schedules-list',
				position: 'left-top',
				onNext: function() {
					dash_right_next();
				},
			},
			{
				title: '<h2>Schedule Description</h2>',
				content: 'Enter a brief description of this schedule by clicking "add a description".  You can also edit the description by clicking the text.',
				selector: '#description-section',
				position: 'left-top'
			},
			{
				title: '<h2>Schedule Actions</h2>',
				content: 'From here you can:<ul><li>Click <b>"VIEW"</b> to load an interactive visualization of this schedule</li><li>Click <b>"EDIT"</b> to load this schedule into the drag-and-drop editor</li><li>Click <b>"COPY"</b> to create a new schedule with all the same data as this one</li><li>Click <b>"DELETE"</b> to remove this schedule.</li></ul>',
				selector: '#actions-section',
				position: 'left-center'
			},
			{
				title: '<h2>Support Tickets</h2>',
				content:  'If there are any open support tickets for this schedule, they will show up here. You will also see a link to navigate to the ticket manager.',
				position: 'left-top',
				selector: '#tickets-section'
			},
			{
				title: '<h2>That\'s it!</h2>',
				content: 'You can view this tutorial again by clicking the "Help" link in the navigation bar.',
				position: 'center-center',
				selector: '#center-slide-anchor'
			}
		],
		onStart: function() {
			$('#custom_navbar').css('box-shadow', '0 0 0 #000');
		},
		onStop: function() {
			tour_ended = true;
			$('#close-btn').first().click();
			$('body').off('DOMNodeInserted');
			$('#custom_navbar').css('box-shadow', nav_shadow).zIndex(999);
		}
	}, 'dash');	
}

function dash_right_next()
{
	if (!$('.description-section').length)
	{
		$('body').on('DOMNodeInserted', function(e) {
			var el = $(e.target);
			if (el.is('.description-section'))
				$.tutorialize.next('dash');
		});
		$('.sched-list-row').first().click();
	}
	else
		$.tutorialize.next('dash');
}