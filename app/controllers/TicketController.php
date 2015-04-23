<?php

class TicketController extends BaseController 
{
	public function load_ticket_manager($schedule_id)
	{
		// Fetch all of the current user's schedules
		$schedules = Auth::user()->schedules;
		
		if (Auth::user()->schedules->contains($schedule_id)) {
			$schedule = Schedule::find($schedule_id);
		} else {
			$schedule = null;
		}
		
		if(!$schedule)
			return Redirect::route('dashboard');

		$tickets = json_encode($schedule->tickets());

		return View::make('ticket-manager')->with([
			'page_name'	=>	'Ticket Manager',
			'schedules'	=>	$schedules,
			'tickets' 	=>  $tickets,
			'selected' 	=>  $schedule_id
		]);
	}

	public function resolve()
	{
		$ticket_id  = Input::get('ticket_id');
		$ticket = Ticket::find($ticket_id);
		$user = Auth::user();

		if(!$ticket || !$user->schedules->contains($ticket->event->schedule->id))
		{
			return Response::json(['error' => 'Could not resolve the ticket at this time'], 500);
		}

		$ticket->resolve = 1;

		if(!$ticket->save())
		{
			return Response::json(['error' => 'Could not resolve the ticket at this time'], 500);
		}

		return Response::json(['success' => 'Ticket resolve!'], 200);
	}

	public function resolve_all_for_event()
	{
		$event_id = Input::get('event_id');
		$event = models\Event::find($event_id);
		
		if(!$event || !Auth::user()->schedules->contains($event->schedule->id))
		{
			return Response::json(['error' => $event], 500);
		}

		$tickets_to_resolve = $event->tickets;

		foreach ($tickets_to_resolve as $ticket) 
		{
			$ticket->resolve = 1;
			if(!$ticket->save())
			{
				return Response::json(['error' => 'Could not resolve all tickets'], 500);
			}
		}

		return Response::json(['success' => 'All Tickets resolved!'], 200);
	}

	public function load_schedule($schedule_id)
	{
		if (Auth::user()->schedules->contains($schedule_id)) {
			$schedule = Schedule::find($schedule_id);
		} else {
			$schedule = null;
		}
		
		if (!$schedule) {
			return Response::json(['error' => 'Invalid schedule id'], 500);
		}

		$tickets = $schedule->tickets();
		return $tickets;
	}

	public function add_ticket()
	{
		$event_id = Input::get('event_id');
		$ticket_message = Input::get('message');

		if(strlen($ticket_message) < 5)
		{
			return Response::json(['error' => 'message must be at least 5 characters'], 500);
		}

		$event = models\Event::find($event_id);

		if(!$event)
		{
			return Response::json(['error' => 'Could not find the class requested'], 500);
		}

		$ticket = Ticket::firstOrCreate(array(
			'event_id' => $event->id,
			'message'  => $ticket_message,
			'resolve'  => 0,
			'user'	   => Auth::user()->id ? Auth::user()->id : -1
		));

		if(!$ticket)
		{
			return Response::json(['error' => 'Could not create the requested ticket'], 500);
		}

		$user = $event->schedule->users->first();
		if($user && $user->send_email)
		{
			try
			{
				Mail::send('emails.ticket', array('email_message' => $ticket_message), 
					function($message) use ($user, $event)
					{
						$message->to($user->email, $user->username)->subject('Ticket Created for ' . htmlspecialchars($event->name));
					}
				);
			}
			catch(Exception $e){
				return Response::json(['error' => $e->getMessage()], 500);
			}
		}

		return Response::json(['sucess' => "added ticket"], 200);

	}
}
