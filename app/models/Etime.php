<?php

class Etime extends Eloquent 
{
	protected $table = 'etimes';
	protected $fillable = array('starttm','length', 'days', 'event_id');

	public function events()
	{
		return $this->belongsTo('Event');
	}

}