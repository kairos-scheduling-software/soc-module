<?php

class Constraint extends Eloquent 
{
	protected $table = 'constraints';
	protected $fillable = array('key','value', 'event_id');
}