$(function(){
	$('.event-view-link').click(function(e){
		e.preventDefault();
		var name = $(this).attr('data-name');
		//highlightBlock(name);
		alert(name);
		return false;
	});

	$('.view-class-list-row').click(function() {
		$(this).find('a').click();
		return false;
	});
});