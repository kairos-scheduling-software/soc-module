<?php

class Professor extends Eloquent 
{
	protected $table = 'professors';
	protected $fillable = array('name', 'uid');

	public function events()
	{
		return $this->hasMany('Event');
	}

}