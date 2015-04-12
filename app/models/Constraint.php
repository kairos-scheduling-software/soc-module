<?php

class Constraint extends Eloquent 
{
	protected $table = 'constraints';
	protected $fillable = array('key','value', 'event_id');
	protected $touches = array('event');

	public function event()
	{
		return models\Event::find($this->value);
	}
}