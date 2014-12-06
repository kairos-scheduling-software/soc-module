$(function(){
	$('.sched-list-row').click(function(e) {
		e.preventDefault();
		$('#hg-right').css('display', 'table-cell');
	});
	
	$('#close-btn').click(function(e) {
		e.preventDefault();
		$('#hg-right').css('display', 'none');
	});
});