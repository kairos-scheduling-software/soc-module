<?php 
namespace models;

class Event extends \Eloquent 
{
	protected $table = 'events';
	protected $fillable = array('name','professor', 'schedule_id');

	public function constraints()
	{
		return $this->hasMany('Constraint');
	}

	public function etime()
	{
		return $this->hasOne('Etime');
	}
}