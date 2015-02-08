<?php

class Communication 
{
    public static function sendCheck($schedule_id)
    {
    	$jsonBuilder = [];
    	$schedule = Schedule::find($schedule_id);

    	if(is_null($schedule))
    	{
    		throw new Exception('ERROR: The requested schedule could not be found');
    	}

    	$json = Communication::create_backEndJson($schedule);

        $result = Communication::sendJsonToCoreService('check', $json, $schedule_id);

        //do error checking
        $scheduleResults = json_decode($result);

        if(is_object($scheduleResults) && $scheduleResults->Error !== null)
        {
            throw new Exception('ERROR: ' . $scheduleResults->Error);
        }


        return $scheduleResults;
    }

    private static function sendJsonToCoreService($mode, $json, $schedule_id)
    {
        //$host = 'http://scheduling-core-service.herokuapp.com/api/' . $mode;
        $host = 'localhost:8080/api/' . $mode;

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
    	$rooms = $schedule->rooms;

    	$jsonBuilder = [];
    	$eventsBuilder = [];
    	$roomBuilder = [];

    	foreach ($events as $event) 
    	{
            $constraintBuilder = [];
    		$temp = new StdClass;
    		$timeblock = $event->etime;

            //add all of the time and event data
    		$temp->id = $event->id;
    		$temp->days_count = substr_count($timeblock->days, '|') + 1;
    		$temp->duration = $timeblock->length;


            //create the possible start times as a hard constraint
            $possibleStart = [];
            
            foreach (explode('|', $timeblock->days) as $day)
            {
                $startTime = (Communication::convertIntToStringDay($day) . $timeblock->starttm);
                $possibleStart[] = $startTime; // M1200 should look like this caps important
            }

            $temp->pStartTm = $possibleStart;


    		$temp->space = $event->room_id;
    		$temp->max_participants = $event->room->capacity;
    		$temp->persons = $event->professor;


            //create the list of constraints for a single event
            $eConstraints = $event->constraints;

            foreach ($eConstraints as $constraint) 
            {
                $constraintTemp = new StdClass;
                $constraintTemp->key = $constraint->key;
                $constraintTemp->value = $constraint->value;
                $constraintBuilder[] = $constraintTemp; 
            }

            //add the list of constraints for the event
            $temp->constraint = $constraintBuilder;



    		$eventsBuilder[] = $temp;
    	}

    	foreach ($rooms as $room) 
    	{
    		$rTemp = new StdClass;
    		$rTemp->id = $room->id;
    		$rTemp->capacity = $room->capacity;
    		$rTemp->times = $room->availability;
    		$roomBuilder[] = $rTemp;
    	}

    	$jsonBuilder['EVENT'] = $eventsBuilder;
    	$jsonBuilder['SPACE'] = $roomBuilder;

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