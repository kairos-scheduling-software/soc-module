<?php

class RoomGroup extends Eloquent 
{
	protected $table = 'room_groups';
	protected $fillable = array('name', 'description');

	public function rooms()
	{
		return $this->belongsToMany('Room', 'room_mappings', 'id', 'rid')->select('rooms.id', 'rooms.name', 'rooms.capacity');
	}

}
