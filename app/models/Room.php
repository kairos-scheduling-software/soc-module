<?php

class Room extends Eloquent 
{
	protected $table = 'rooms';
	protected $fillable = array('name','capacity', 'schedule_id', 'availability');

}