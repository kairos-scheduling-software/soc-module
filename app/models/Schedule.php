<?php

class Schedule extends Eloquent 
{
	protected $table = 'schedules';
	protected $fillable = array('name','json_schedule', 'description', 'last_edited_by');

	public function users()
	{
		return $this->belongsToMany('User');
	}

	public function events()
	{
		return $this->hasMany('models\Event');
	}

	public function rooms()
	{
		return $this->hasMany('Room');
	}
}
