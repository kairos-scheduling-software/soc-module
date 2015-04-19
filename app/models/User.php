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
		return $this->belongsToMany('Schedule', 'schedule_user')->orderBy('year', 'desc');
	}

	public function update_schedules_for_year_semester($year, $semester)
	{
		$this->belongsToMany('Schedule', 'schedule_user')->where('year', '=', $year)->where('semester', '=', $semester)->update(array('final' => 0));
		return;
	}

	public function sortedSchedules($up_name, $up_year, $up_semester, $up_edit, $primary)
	{
		$primary_semester = false;
		$rest = "";
		$query = $this->belongsToMany('Schedule', 'schedule_user');

		if($up_name && strcmp($primary, 'header_name_col') !== 0)
			$rest .= "name asc, ";
		else
		{
			if(strcmp($primary, 'header_name_col') == 0)
			{
				if($up_name)
				{
					$query->orderBy('name', 'asc');
				}
				else
				{
					$query->orderBy('name', 'desc');
				}
			}
			else
			{
				$rest .= "name desc, ";
			}
		}
		if($up_year && strcmp($primary, 'header_year_col') !== 0)
			$rest .= "year asc, ";
		else
		{
			if(strcmp($primary, 'header_year_col') == 0)
			{
				if($up_year)
				{
					$query->orderBy('year', 'asc');
				}
				else
				{
					$query->orderBy('year', 'desc');
				}
			}
			else
			{
				$rest .= "year desc, ";
			}
		}
		if($up_semester && strcmp($primary, 'header_semester_col') !== 0)
			$rest .= "semester asc";
		else
		{
			if(strcmp($primary, 'header_semester_col') == 0)
			{
				$primary_semester = true;
				if($up_semester)
				{
					$query->orderBy('semester', 'asc');
				}
				else
				{
					$query->orderBy('semester', 'desc');
				}
			}
			else
			{
				$rest .= "semester desc";
			}
		}
		if($up_edit && strcmp($primary, 'header_edit_col') !== 0)
		{
			if(!$primary_semester)
				$rest .= ", ";
			$rest .= "schedules.updated_at asc";
		}
		else
		{
			if(strcmp($primary, 'header_edit_col') == 0)
			{
				if($up_edit)
				{
					$query->orderBy('schedules.updated_at', 'asc');
				}
				else
				{
					$query->orderBy('schedules.updated_at', 'desc');
				}
			}
			else
			{
				if(!$primary_semester)
				$rest .= ", ";
				$rest .= "schedules.updated_at desc";
			}
		}
		
		return $query->orderByRaw($rest)->get();
	}

	public function full_name()
	{
		return $this->first . ' ' . $this->last;
	}

	public function change_password($old_pw, $new_pw)
	{
		if (Hash::check($old_pw, $this->getAuthPassword()))
		{
			$this->password = Hash::make($new_pw);
			return $this->save();
		}
		else
			throw new Exception("Old password does is not correct.");
	}
}
