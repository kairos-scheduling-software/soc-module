$(function(){

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

	$('.panel-heading').mouseenter(function() {
		$(this).addClass('cursor-link');
	}).mouseleave(function() {
		$(this).removeClass('cursor-link');
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
});