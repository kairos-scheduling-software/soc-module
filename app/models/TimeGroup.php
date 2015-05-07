<?php

class TimeGroup extends Eloquent 
{
	protected $table = 'time_groups';
	protected $fillable = array('name');

	public function etimes()
	{
		return $this->belongsToMany('Etime', 'time_mappings', 'id', 'eid');
	}

}
