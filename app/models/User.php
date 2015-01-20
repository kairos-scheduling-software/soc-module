<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface 
{

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';
	protected $fillable = array('username', 'email', 'first', 'last', 'password', 'temp_password', 'active_code', 'active', 'remember_token');

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');

	public function getRememberToken()
	{
    	return $this->remember_token;
	}

	public function setRememberToken($value)
	{
    	$this->remember_token = $value;
	}

	public function getRememberTokenName()
	{
    	return 'remember_token';
	}

	public function schedules()
	{
		return $this->belongsToMany('Schedule');
	}

	public function full_name()
	{
		return $this->first . ' ' . $this->last;
	}

	public function change_password($old_pw, $new_pw)
	{
		if(Hash::make($old_pw) == $this->password)
		{
			$this->password = Hash::make($new_pw);
			return $this->save();
		}
		else
			throw new Exception("Old password does is not correct.");
	}
}
