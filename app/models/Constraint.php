<?php

class Constraint extends Eloquent 
{
	protected $table = 'constraints';
	protected $fillable = array('key','value', 'event_id', 'type');
	protected $touches = array('event');

	public function event()
	{
		return $this->belongsTo('models\Event');
	}
}
