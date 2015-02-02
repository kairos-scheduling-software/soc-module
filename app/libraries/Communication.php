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
        if(Communication::startsWith($result, 'ERROR:'))
        {
            throw new Exception($result);
        }


        return $result;
    }

    private static function sendJsonToCoreService($mode, $json, $schedule_id)
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
    		$temp->pStarttM = $timeblock->starttm;
    		$temp->days = $timeblock->days;
            $temp->days_count = substr_count($timeblock->days, '|');
    		$temp->starttm = $timeblock->starttm;
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
}