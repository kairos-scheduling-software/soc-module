function createTable(tickets)
{
	var dynamic = '';
	var prevName = '';
	var count = 0;

	var hiddenRows = 0;
	var hiddenBuilder = '<div class="ticket-list-hidden-row hidden" id="hidden_' + hiddenRows + '">';

	if(tickets.length == 0)
	{
		$('#ticketsTable').append("<h2>There are no outstanding tickets for this schedule.</h2>");
		return;
	}

	for(i = 0; i < tickets.length; i++)
	{
		if(i != 0 && prevName != tickets[i]['name'])
		{
			dynamic = dynamic + '<div class=\'ticket-list-row\' onclick="toggleHidden(\'hidden_' + hiddenRows + '\');">'
					+ '<div class=\'ticket-event\'>' + prevName
					+ '</div><div class=\'ticket-event\'>' + count + '</div>'
					+ '<div class=\'ticket-event\'>'
					+ '<input type="button" onclick="event.cancelBubble=true;alert(\'input\');" value=\'resolve all\'/>'
					+ '</div></div>'
					+ hiddenBuilder + '</div>';

			hiddenRows++;
			hiddenBuilder = '<div class="ticket-list-hidden-row hidden" id="hidden_' + hiddenRows + '">';
			count = 0;
		}

		hiddenBuilder = hiddenBuilder + '<div class="ticket-message-row">' + tickets[i]['message'] + '</div>' 
						+ '<div class="ticket-resolve-row">' 
						+ '<input type="button" onclick="event.cancelBubble=true;alert(\'input\');" value="resolve ticket"/>' 
						+ '</div>';

		count++;
		prevName = tickets[i]['name'];
	}

	dynamic = dynamic + '<div class=\'ticket-list-row\' onclick="toggleHidden(\'hidden_' + hiddenRows + '\');">'
					+ '<div class=\'ticket-event\'>' + prevName
					+ '</div><div class=\'ticket-event\'>' + count + '</div>'
					+ '<div class=\'ticket-event\'>'
					+ '<input type="button" onclick="event.cancelBubble=true;alert(\'input\');" value=\'resolve all\'></input>'
					+ '</div></div>'
					+ hiddenBuilder + '</div>';

	$('#ticketsTable').append(dynamic);
}

function toggleHidden(rowToToggle)
{
	$('#' + rowToToggle).toggleClass('hidden');
}

function requestSchedule()
{

	$('#ticketsTable').empty();
	var url = $('#schedules option:selected').attr('data-url');

	$.ajax({
		url:		url,
		type: 		"POST", 
		success: 	function(data, textStatus, jqXHR) 
		{
			createTable(data);
			
		},
		error: 		function(jqXHR, textStatus, errorThrown) 
		{
			alert('Could not find the requested schedule');
		}
	});
}

function resolve(ticket)
{

}

function resolveAll(event)
{

}