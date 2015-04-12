<?php 
namespace models;

class Event extends \Eloquent 
{
	protected $table = 'events';
	protected $fillable = array('name','professor', 'schedule_id', 'room_id', 'class_type', 'title', 'etime_id');
	protected $touches = array('schedule');

	public function constraints()
	{
		return $this->hasMany('Constraint');
	}

	public function etime()
	{
		return $this->belongsTo('Etime');
	}

	public function room()
	{
		return $this->belongsTo('Room');
	}

	public function tickets()
	{
		return $this->hasMany('Ticket');
	}

	public function schedule()
	{
		return \Schedule::find($this->schedule_id);
	}
}