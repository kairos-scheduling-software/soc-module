<?php

class Schedule extends Eloquent 
{
	protected $table = 'schedules';
	protected $fillable = array('name','json_schedule', 'description', 'year', 'semester', 'final' ,'last_edited_by');

	public function users()
	{
		return $this->belongsToMany('User', 'schedule_user');
	}

	public function events()
	{
		return $this->hasMany('models\Event');
	}

	public function rooms()
	{
		return $this->belongsToMany('Room', 'room_schedule');
	}

	public function professors()
	{
		return $this->belongsToMany('Professor', 'professor_schedule');
	}

	public function tickets()
	{
		return DB::table('events')
			->join('tickets', 'events.id', '=', 'tickets.event_id')
			->select('events.id', 'tickets.id as ticket_id' ,'events.name', 'tickets.message')
			->where('events.schedule_id', '=', $this->id)
			->where('tickets.resolve', '=', 0)
			->orderBy('events.name')
			->get();
	}

	public function removeConstraints()
	{
		foreach ($this->events as $event) 
		{
			$event->constraints()->delete();
		}
	}

	public function to_json()
	{
		$events = $this->events;

		$jsonBuilder = [];

		foreach($events as $value)
		{

			$timeblock = $value->etime;
			$room = Room::find($value->room_id)->name;
			$professor = Professor::find($value->professor)->name;

			if($timeblock)
			{
				foreach (explode('|', $timeblock->days) as $day)
				{
					$temp = new StdClass;
					$temp->id = $value->id;
					$temp->starttm = $timeblock->starttm;
					$temp->length = $timeblock->length;
					$temp->day = $day;
					$temp->name = $value->name;
					$temp->class_type = $value->class_type;
					$temp->title = $value->title;
					$temp->room = $room;
					$temp->professor = $professor;
    				$jsonBuilder[] = $temp;
    			}
			}
			else
			{
				$temp = new StdClass;
				$temp->id = $value->id;
				$temp->starttm = "0";
				$temp->length = "0";
				$temp->day = "0";
				$temp->name = $value->name;
				$temp->class_type = $value->class_type;
				$temp->title = $value->title;
				$temp->room = $room;
				$temp->professor = $professor;
    			$jsonBuilder[] = $temp;
				$jsonBuilder[] = $temp;
			}
		}

		return json_encode($jsonBuilder);
	}
}