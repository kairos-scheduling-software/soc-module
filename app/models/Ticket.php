<?php

class Ticket extends Eloquent 
{
	protected $table = 'tickets';
	protected $fillable = array('event_id','message');
}