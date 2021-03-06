<?php

class Communication 
{
    public static function sendCheck($schedule)
    {
    	$jsonBuilder = [];
    	//$schedule = Schedule::find($schedule_id);

    	if($schedule == null)
    	{
            $result = new StdClass;
    		$result->Error = 'The requested schedule could not be found';
            return $result;
    	}

    	$json = Communication::create_backEndJson($schedule);

        $result = Communication::sendJsonToCoreService('new', $json);
        $scheduleResults = json_decode($result);

        return $scheduleResults;
    }

    private static function sendJsonToCoreService($mode, $json)
    {
        $host = 'http://scheduling-core-service.herokuapp.com/api/' . $mode;
        //$host = 'localhost:8080/api/' . $mode;

		//will need to set up
		$curl = curl_init($host);
  		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
  		curl_setopt($curl, CURLOPT_HEADER, false);
  		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  		curl_setopt($curl, CURLOPT_HTTPHEADER,
    	array("Content-type: application/json"));
  		curl_setopt($curl, CURLOPT_POST, true);
  		curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
		

		$result     = curl_exec($curl);
		curl_close($curl);

		return $result;
    }

    public static function create_backEndJson($schedule)
    {

    	$events = $schedule->events;
    	//$rooms = $schedule->rooms;
        $rooms = Room::select('id', 'capacity')->get();

    	$jsonBuilder = [];
    	$eventsBuilder = [];
    	$roomBuilder = [];
        $constraintBuilder = [];

        $jsonBuilder['APIKey'] = "1bb0ea87-d786-4300-903d-e3aa4e3ac670";

    	foreach ($events as $event) 
    	{
    		$temp = new StdClass;
    		$timeblock = $event->etime;

            //add all of the time and event data
    		$temp->id = $event->id;
    		//$temp->days_count = substr_count($timeblock->days, '|') + 1;
            //$temp->duration = $timeblock->length;
            
            $temp->time = new StdClass;
            $temp->time->duration = $timeblock->length;
            
            if ($event->class_type == 'LABORATORY')
                $temp->time->duration -= 15;
    		
            //create the possible start times as a hard constraint
            $possibleStart = [];
            $startTime = [];
            $starttmArray = [];

            $possibleDays = "";
            
            foreach (explode('|', $timeblock->days) as $day)
            {
                $possibleDays .= Communication::convertIntToStringDay($day);
            }
            
            $starttmArray[] = $timeblock->starttm;
            $startTime[$possibleDays] = $starttmArray;

            $possibleStart = $startTime; // MW: [1200] should look like this caps important
            $temp->time->startTimes = $possibleStart;

            //start moving on to the spaces

    		if ($event->is_rm_final == true && $event->room_id != null) {
                $temp->spaceId = $event->room_id;
            } else {
                $rm_grp_id = $event->room_group_id;
                $rm_list = $rooms;
                if ($rm_grp_id != null) {
                    //~ $rm_list = Room::select('id')->join('room_mappings', 'rooms.id', '=', 'room_mappings.rid')
                            //~ ->where('room_mappings.name', '=', $rm_grp)->get();
                    $rm_list = RoomGroup::find($rm_grp_id)->rooms;
                }
                $spaces = [];
                foreach ($rm_list as $rm) {
                    $spaces[] = $rm->id;
                }
                $temp->spaceId = $spaces;
            }
            
            
    		$cap = $event->enroll_cap;
            if ($cap == null) {
                $cap = 0;
                //$cap = $event->room->capacity;
            }
            $temp->maxParticipants = $cap;
    		$temp->personId = $event->professor;


            //create the list of constraints for a single event
            $eConstraints = $event->constraints;

            foreach ($eConstraints as $constraint) 
            {
                $constraintTemp = [];
                $key = $constraint->key;

                $constraintTemp[] = $constraint->event_id;
                $constraintTemp[] = $constraint->value;

                $constraintBuilder[$key][] = $constraintTemp; 
            }


    		$eventsBuilder[] = $temp;
    	}

    	foreach ($rooms as $room) 
    	{
    		$rTemp = new StdClass;
    		$rTemp->id = $room->id;
    		$rTemp->capacity = $room->capacity;
    		//$rTemp->times = $room->availability;
    		$roomBuilder[] = $rTemp;
    	}

    	$jsonBuilder['EVENTS'] = $eventsBuilder;
    	$jsonBuilder['SPACES'] = $roomBuilder;

        $jsonBuilder['CONSTRAINTS'] = count($constraintBuilder) != 0 ? $constraintBuilder: (object)null;

    	$json = json_encode($jsonBuilder);
    	return $json;
    }

    private static function startsWith($toCheck, $starts)
    {
        $length = strlen($starts);
        return (substr($toCheck, 0, $length) === $starts);
    }

    private static function convertIntToStringDay($day)
    {
        $stringDay = 'M';
        switch ($day) 
        {
            case 1:
                $stringDay = 'M';
                break;
            case 2:
                $stringDay = 'T';
                break;
            case 3:
                $stringDay = 'W';
                break;
            case 4:
                $stringDay = 'H';
                break;
            case 5:
                $stringDay = 'F';
                break;
            case 6:
                $stringDay = 'S';
                break;
            case 7:
                $stringDay = 'U';
                break;
            default:
                $stringDay = 'M';
        }

        return $stringDay;
    }
}
