<?php

class Ticket extends Eloquent 
{
	protected $table = 'tickets';
	protected $fillable = array('event_id','message');
	
	public function event()
	{
		return $this->belongsTo('Event');
	}
}
