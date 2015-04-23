var event_counts = {};
var hiddenRows = 0;

function createTable(tickets)
{
	event_counts = {}
	hiddenRows = 0;

	var dynamic = '';
	var prevName = '';
	var count = 0;

	var hiddenBuilder = '<div class="ticket-list-hidden-row hidden" id="hidden_' + hiddenRows + '">';

	if(tickets.length == 0)
	{
		$('#ticketsTable').append("<h2>There are no outstanding tickets for this schedule.</h2>");
		return;
	}

	for(i = 0; i <= tickets.length; i++)
	{
		isSameElement = (i == tickets.length ? true : prevName != tickets[i]['name']);
		if(i != 0 && isSameElement)
		{
			dynamic = dynamic + '<div id="row_' + hiddenRows + '" class="ticket-list-row" onclick="toggleHidden(\'' + hiddenRows + '\');">'
					+ '<div class="ticket-event">' + prevName
					+ '</div><div id="ticketCount_' + hiddenRows + '" class="ticket-event">' + count + '</div>'
					+ '<div class="ticket-event resolve-button-element">'
					+ '<i class="fa fa-check" onclick="event.cancelBubble=true;resolveAll(this, \'' + hiddenRows + '\' ,  \'' + tickets[i - 1]['id'] + '\');" value=\'resolve all\'/>'
					+ '</div></div>'
					+ hiddenBuilder + '</div>';

			event_counts[hiddenRows] = count;
			hiddenRows++;
			hiddenBuilder = '<div class="ticket-list-hidden-row hidden" id="hidden_' + hiddenRows + '">';
			count = 0;

			if(i == tickets.length)
				break;
		}

		var message = tickets[i]['message'];

		hiddenBuilder = hiddenBuilder + '<div class="container-row-hidden"><div class="ticket-message-row">' + message + '</div>' 
						+ '<div class="ticket-resolve-row resolve-button-element">' 
						+ '<i class="fa fa-check" onclick="event.cancelBubble=true;resolve(this, \'' + hiddenRows + '\' , \''+ tickets[i]['ticket_id'] +'\');" value="resolve ticket"/>' 
						+ '</div></div>';

		count++;
		prevName = tickets[i]['name'];
	}

	$('#ticketsTable').append(dynamic);
}

function toggleHidden(row)
{
	$('#hidden_' + row).toggleClass('hidden');
	$('#row_' + row).toggleClass('highlight');
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

function resolve(element, rows ,ticket_id)
{
	var url = $('#resolve').attr('data-url');

	$.ajax({
		url:		url,
		type: 		"POST",
		data: { ticket_id: ticket_id },  
		success: 	function(data, textStatus, jqXHR) 
		{
			event_counts[rows] -= 1;
			$(element).parent().parent().remove();
			$("#ticketCount_" + rows).html(event_counts[rows]);


			if(event_counts[rows] == 0)
			{
				hiddenRows--;
				$("#hidden_" + rows).remove();
				$('#row_' + rows).remove();

				if(hiddenRows == 0)
				{
					$('#ticketsTable').html("<h2>There are no outstanding tickets for this schedule.</h2>");
				}
			}
			
		},
		error: 		function(jqXHR, textStatus, errorThrown) 
		{
			alert("could not resolve ticket at this time");
		}
	});
}

function resolveAll(element, rows , event_id)
{
	var url = $('#resolve_all').attr('data-url');

	$.ajax({
		url:		url,
		type: 		"POST",
		data: { event_id: event_id },  
		success: 	function(data, textStatus, jqXHR) 
		{
			event_counts[rows] = 0;
			$(element).parent().parent().remove();
			$("#hidden_" + rows).remove();
			hiddenRows--;

			if(hiddenRows == 0)
			{
				$('#ticketsTable').html("<h2>There are no outstanding tickets for this schedule.</h2>");
			}
			
		},
		error: 		function(jqXHR, textStatus, errorThrown) 
		{
			alert("could not resolve tickets at this time");
		}
	});
}