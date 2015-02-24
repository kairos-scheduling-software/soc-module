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

	public function tickets()
	{
		return DB::table('events')
			->join('tickets', 'events.id', '=', 'tickets.event_id')
			->select('events.id', 'events.name', 'tickets.message')
			->where('events.schedule_id', '=', $this->id)
			->orderBy('events.name')
			->get();
	}

	public function to_json()
	{
		$events = $this->events;

		$jsonBuilder = [];

		foreach($events as $value)
		{

			$timeblock = $value->etime;
			$room = Room::find($value->room_id)->name;

			if($timeblock)
			{
				foreach (explode('|', $timeblock->days) as $day)
				{
					$temp = new StdClass;
					$temp->starttm = $timeblock->starttm;
					$temp->length = $timeblock->length;
					$temp->day = $day;
					$temp->name = $value->name;
					$temp->class_type = $value->class_type;
					$temp->title = $value->title;
					$temp->room = $room;
    				$jsonBuilder[] = $temp;
    			}
			}
			else
			{
				$temp = new StdClass;
				$temp->starttm = "0";
				$temp->length = "0";
				$temp->day = "0";
				$temp->name = $value->name;
				$temp->class_type = $value->class_type;
				$temp->title = $value->title;
				$temp->room = $room;
    			$jsonBuilder[] = $temp;
				$jsonBuilder[] = $temp;
			}
		}

		return json_encode($jsonBuilder);
	}
}