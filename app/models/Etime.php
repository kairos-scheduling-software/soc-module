<?php

class Etime extends Eloquent 
{
	protected $table = 'etimes';
	protected $fillable = array('starttm','length', 'days', 'standard_block');

	public function events()
	{
		return $this->hasMany('models\Event');
	}

}