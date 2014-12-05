<?php

class APIController extends BaseController {

	public function create($scheduleId)
	{
		$apiKey = '57C4A';

		$schedule = Schedule::find($scheduleId);

		$events = $schedule->events;

		//create the json
		$scheduleCreator['api-key'] = $apiKey;

		$scheduleCreator['events'] = array();

		foreach($events as $v)
		{
			$constraints = $v->constraints;

			$jsonConstraints = array();
			
			foreach ($constraints as $value) 
			{
				$jsonConstraints[] = array(
					$value->key => $value->value,
					'type' => $value->type
				);
			}

			$scheduleCreator['events'][] = array(
				'id' => $v->id,
				'name'=> $v->name,
				'constraints' => $jsonConstraints
				);

		}


		$scheduleJSON = json_encode($scheduleCreator);

		//will need to set up
		$curl = curl_init('http://scheduling-core-service.herokuapp.com/');
  		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
  		curl_setopt($curl, CURLOPT_HEADER, false);
  		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  		curl_setopt($curl, CURLOPT_HTTPHEADER,
    	array("Content-type: application/json"));
  		curl_setopt($curl, CURLOPT_POST, true);
  		curl_setopt($curl, CURLOPT_POSTFIELDS, $scheduleJSON);
		

		$result     = curl_exec($curl);
		$response   = json_decode($result);
		curl_close($curl);
		
		//check the response
		echo $result;
	}

}