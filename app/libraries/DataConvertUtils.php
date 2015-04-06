<?php

class DataConvertUtils
{
	public static function convertIntToStringDay($day)
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

    public static function convertStringToIntDay($day)
    {
        $intDay = 1;
        switch ($day) 
        {
            case 'M':
                $intDay = 1;
                break;
            case 'T':
                $intDay = 2;
                break;
            case 'W':
                $intDay = 3;
                break;
            case 'H':
                $intDay = 4;
                break;
            case 'F':
                $intDay = 5;
                break;
            case 'S':
                $intDay = 6;
                break;
            case 'U':
                $intDay = 7;
                break;
            default:
                $intDay = 1;
        }

        return $intDay;
    }

    public static function convertKey($tempKey)
    {
        $key = "";
        
        if(strcasecmp($tempKey, 'before') == 0)
        {
            $key = "<";
        }
        else if(strcasecmp($tempKey, 'after') == 0)
        {
            $key = ">";
        }
        else if(strcasecmp($tempKey, 'equal') == 0)
        {
            $key = "=";
        }
        else if(strcasecmp($tempKey, 'not') == 0)
        {
            $key = "!";
        }
        return $key;
    }

    public static function importConstraint($schedule, $importFile)
    {
        //Start parsing the file
        $file = fopen($importFile, "r");

        $failures['percentPassed'] = 100;
        $failures['rowsFailed'] = [];

        $column_headers = array();
        $row_count = 0;

        while (!feof($file)) 
        {
            $row = fgetcsv($file, 1000);

            //this is not a real row
            if(!$row)
            {
                continue;
            }

            if ($row_count==0)
            {
                $column_headers = $row;
                
                if(!DataConvertUtils::verifyHeaders($column_headers, 'constraint'))
                {
                    throw new exception("Error: invalid headers");
                }

                $row_count += 1;
                continue;
            }
            $row_count++;

            //if the row doesn't have enough columns skip it
            //eventually use this as part of the % bad data
            if(count($row) !== 3)
            {
                $failures['rowsFailed'][] = $row_count;
                continue;
            }

            //check if the data is in the correct format using regex and other such methods
            $event = models\Event::where('schedule_id', '=', $schedule->id)->where('name', '=', $row[0])->first();
            $event2 = models\Event::where('schedule_id', '=', $schedule->id)->where('name', '=', $row[1])->first();

            $key = DataConvertUtils::convertKey($row[2]);

            //it failed if the event couldn't be found or the key is not supported
            if($key == "" || $event === null || $event2 === null)
            {
                $failures['rowsFailed'][] = $row_count;
                continue;
            }

            $constraint = Constraint::firstOrCreate(array(
                'event_id'  => $event->id,
                'value'     => $event2->id,
                'key'       => $key
            ));
        }

        fclose($file);

        //minus the value of the header row during final failure calculation 
        //since that row will already be verified if it gets here
        $row_count = $row_count - 1;
        $failures['percentPassed'] = ($row_count - count($failures['rowsFailed'])) / $row_count;
        return $failures;
    }

    public static function importRooms($schedule, $importFile)
    {
        //Start parsing the file
        $file = fopen($importFile, "r");

        $failures['percentPassed'] = 100;
        $failures['rowsFailed'] = [];

        $column_headers = array();
        $row_count = 0;
        while (!feof($file)) 
        {
            $row = fgetcsv($file, 1000);

            //this is not a real row
            if(!$row)
            {
                continue;
            }
                
            if ($row_count== 0)
            {
                $column_headers = $row;
                
                if(!DataConvertUtils::verifyHeaders($column_headers, 'room'))
                {
                    throw new exception("Error: invalid headers");
                }

                $row_count += 1;
                continue;
            }
            $row_count += 1;

            //if the row doesn't have enough columns skip it
            //if any of the rows fail the regex check fail it
            if(count($row) != 2 || !is_numeric($row[1]) || $row[1] < 0) 
            {
                $failures['rowsFailed'][] = $row_count;
                continue;
            }

            $room = Room::firstOrCreate(
            array(
                'name' => $row[0],
                'capacity' => $row[1]
            ));

            if (!($schedule->rooms->contains($room->id))) 
            {
                $schedule->rooms()->sync([$room->id], false);
            }
        }

        fclose($file);

        //minus the value of the header row during final failure calculation 
        //since that row will already be verified if it gets here
        $row_count = $row_count - 1;
        $failures['percentPassed'] = ($row_count - count($failures['rowsFailed'])) / $row_count;

        return $failures;
    }

    public static function importProfessors($schedule, $importFile)
    {
        //Start parsing the file
        $file = fopen($importFile, "r");

        $failures['percentPassed'] = 100;
        $failures['rowsFailed'] = [];

        $column_headers = array();
        $row_count = 0;
        while (!feof($file)) 
        {
            $row = fgetcsv($file, 1000);

            //this is not a real row
            if(!$row)
            {
                continue;
            }
                
            if ($row_count== 0)
            {
                $column_headers = $row;
                
                if(!DataConvertUtils::verifyHeaders($column_headers, 'professor'))
                {
                    throw new exception("Error: invalid headers");
                }

                $row_count += 1;
                continue;
            }
            $row_count += 1;

            //if the row doesn't have enough columns skip it
            //if any of the rows fail the regex check fail it
            if(count($row) != 2 || !preg_match('/^U[0-9]{7}$/', $row[0]))
            {
                $failures['rowsFailed'][] = $row_count;
                continue;
            }

            $professor = Professor::firstOrNew(
            array(
                'uid'  => $row[0]
            ));

            if(!isset($professor->name))
            {
                $professor->name = $row[1];
                $professor->save();
            }

            if (!($schedule->professors->contains($professor->id))) 
            {
                $schedule->professors()->sync([$professor->id], false);
            }
        }

        fclose($file);

        //minus the value of the header row during final failure calculation 
        //since that row will already be verified if it gets here
        $row_count = $row_count - 1;
        $failures['percentPassed'] = ($row_count - count($failures['rowsFailed'])) / $row_count;

        return $failures;
    }

    public static function importFullSchedule($schedule, $importFile)
    {
        //Start parsing the file
        $file = fopen($importFile, "r");

        $failures['percentPassed'] = 100;
        $failures['rowsFailed'] = [];

        $column_headers = array();
        $row_count = 0;
        while (!feof($file)) 
        {
            $row = fgetcsv($file, 1000);

            //this is not a real row
            if(!$row)
            {
                continue;
            }
                
            if ($row_count== 0)
            {
                $column_headers = $row;
                
                if(!DataConvertUtils::verifyHeaders($column_headers, 'full'))
                {
                    throw new exception("Error: invalid headers");
                }

                $row_count += 1;
                continue;
            }
            $row_count += 1;

            //if the row doesn't have enough columns skip it
            //if any of the rows fail the regex check fail it
            if(count($row) != 9 || !DataConvertUtils::verifyRow($row))
            {
                $failures['rowsFailed'][] = $row_count;
                continue;
            }

            $room = Room::firstOrCreate(
            array(
                'name' => $row[0],
                'capacity' => $row[1]
            ));

            $professor = Professor::firstOrNew(
            array(
                'uid'  => $row[2]
            ));

            if(!isset($professor->name))
            {
                $professor->name = $row[3];
                $professor->save();
            }

            if (!($schedule->rooms->contains($room->id))) 
            {
                $schedule->rooms()->sync([$room->id], false);
            }
            if (!($schedule->professors->contains($professor->id))) 
            {
                $schedule->professors()->sync([$professor->id], false);
            }
               
            $days = '';
            $time = '';

            foreach (explode('|', $row[7]) as $value) 
            {
                if(strlen($days) > 0)
                {
                    $days .= '|';
                }

                $days .= DataConvertUtils::convertStringToIntDay(substr($value, 0, 1));
                $time = substr($value, 1);
            }

            if(strlen($days) > 0 && strlen($time) == 4)
            {
                $timeblock = Etime::firstOrCreate(
                array(
                   'starttm' => $time,
                   'length' => $row[8],
                   'days' => $days
                ));

                if($timeblock->standard_block == null)
                {
                    $timeblock->standard_block = 0;
                    $timeblock->save();
                }
            }

            $event = models\Event::create(
            array(
                'name' => $row[4],
                'professor' => $professor->id,
                'schedule_id' => $schedule->id,
                'room_id' => $room->id,
                'class_type' => $row[6],
                'title' => $row[5],
                'etime_id' => $timeblock ? $timeblock->id : 1
            ));
        }

        fclose($file);

        //minus the value of the header row during final failure calculation 
        //since that row will already be verified if it gets here
        $row_count = $row_count - 1;
        $failures['percentPassed'] = ($row_count - count($failures['rowsFailed'])) / $row_count;

        return $failures;
    }

    private static function verifyHeaders($headers, $mode)
    {
        $verify = true;
        
        if($mode == 'constraint')
        {
            if(count($headers) != 3)
            {
                return false;
            }

            $verify = $verify && (strcasecmp($headers[0], 'Class') == 0); 
            $verify = $verify && (strcasecmp($headers[1], 'Class') == 0); 
            $verify = $verify && (strcasecmp($headers[2], 'Key') == 0); 
        }
        else if($mode == 'room')
        {
            $verify = $verify && (strcasecmp($headers[0], 'Room') == 0); 
            $verify = $verify && (strcasecmp($headers[1], 'Capacity') == 0); 
        }
        else if($mode == 'professor')
        {
            $verify = $verify && (strcasecmp($headers[0], 'UUID') == 0); 
            $verify = $verify && (strcasecmp($headers[1], 'Professor') == 0); 
        }
        else
        {
            if(count($headers) != 9)
            {
                return false;
            }

            $verify = $verify && (strcasecmp($headers[0], 'Room') == 0); 
            $verify = $verify && (strcasecmp($headers[1], 'Capacity') == 0); 
            $verify = $verify && (strcasecmp($headers[2], 'UUID') == 0); 
            $verify = $verify && (strcasecmp($headers[3], 'Professor') == 0); 
            $verify = $verify && (strcasecmp($headers[4], 'Class') == 0); 
            $verify = $verify && (strcasecmp($headers[5], 'Title') == 0);
            $verify = $verify && (strcasecmp($headers[6], 'Type') == 0); 
            $verify = $verify && (strcasecmp($headers[7], 'Time') == 0); 
            $verify = $verify && (strcasecmp($headers[8], 'Length') == 0); 
        }
        //additional modes can be added

        return $verify;
    }

    /*
    * Only supports full schedule import for now
    */
    private static function verifyRow($row)
    {
        if (!preg_match('/^[0-9]*$/', $row[1]))
        {
            return false;
        }
        else if(!preg_match('/^U[0-9]{7}$/', $row[2]))
        {
            return false;
        }
        else if(!DataConvertUtils::checkTypeForSupported($row[6]))
        {
            return false;
        }
        else if(!preg_match('/^[m,t,w,h,f,M,T,W,H,F][0-9]{4}((\|[m,t,w,h,f,M,T,W,H,F][0-9]{4})?)*$/', $row[7]))
        {
            return false;
        }
        else if(!preg_match('/^[0-9]*$/', $row[8]))
        {
            return false;
        }

        return true;
    }

    private static function checkTypeForSupported($type)
    {
        $verify = false;
        if((strcasecmp($type, 'Laboratory') == 0))
        {
            $verify = true;
        }
        else if((strcasecmp($type, 'Discussion') == 0))
        {
            $verify = true;
        }
        else if((strcasecmp($type, 'Lecture') == 0))
        {
            $verify = true;
        }
        else if((strcasecmp($type, 'Seminar') == 0))
        {
            $verify = true;
        }
        else if((strcasecmp($type, 'Special Topics') == 0))
        {
            $verify = true;
        }

        return $verify;
    }

    public static function convertJsonAndRoomCSVtoImportCSV($jsonFile, $roomCSV, $toWrite)
    {
        //Start parsing the file
        $file = fopen($roomCSV, "r");
        
        $column_headers = array();
        $parsedRoom_csv = array();
        $row_count = 0;
        while (!feof($file)) 
        {
            $row = fgetcsv($file, 1000);
                
            if ($row_count==0)
            {
                $column_headers = $row;
                $row_count += 1;
                continue;
            }

            $parsedRoom_csv[$row[0]] = $row[4];

            $row_count += 1; 
        }

        fclose($file);

        $string = file_get_contents($jsonFile);
        $json=json_decode($string);

        $titles = [];
        $events = [];

        //add the headers
        $event = array();
        $event['Room'] = 'Room';
        $event['Capacity'] = 'Capacity';
        $event['UUID'] = 'UUID';
        $event['Professor'] = 'Professor';
        $event['Event'] = 'Class';
        $event['Title'] = 'Title';
        $event['Type'] = 'Type';
        $event['Time']  = 'Time';
        $event['Length'] = 'Length';
        $events[] = $event;

        $fakeUID = 1523543;

        foreach ($json as $value) 
        {
            $fakeUID++;
            //set the title in the 
            if(isset($titles[$value->title]))
            {
                $hasTime = false;
                $temp = DataConvertUtils::convertIntToStringDay($value->day) . $value->starttm;
                foreach ($titles[$value->title] as $time) 
                {
                   if($temp == $time)
                   {
                        $hasTime = true;
                   }
                }

                if($hasTime)
                {
                    continue;
                }
            }
            
            $titles[$value->title][] = DataConvertUtils::convertIntToStringDay($value->day) . $value->starttm;

            if(isset($events[$value->name]))
            {
                $events[$value->name]['Time'] .= "|". DataConvertUtils::convertIntToStringDay($value->day) . $value->starttm;
            }
            else
            {
                $event = array();
                $event['Room'] = $value->room;
                $event['Capacity'] = isset($parsedRoom_csv[$value->room]) ? $parsedRoom_csv[$value->room] : 0;
                $event['UUID']  = 'U' . $fakeUID;   //fake
                $event['Professor'] = 'Jim' . $fakeUID;
                $event['Event'] = $value->name;
                $event['Title'] = $value->title;
                $event['Type']  = $value->class_type;
                $event['Time'] = DataConvertUtils::convertIntToStringDay($value->day) . $value->starttm;
                $event['Length'] = $value->length;
                $events[$value->name] = $event;
            }   
        }

        $fp = fopen($toWrite, 'w');

        foreach($events as $event)
        {
             fputcsv($fp, $event);
        }
        
        fclose($fp);
    }

    
    public static function temp($CSV, $roomCSV, $pathToWrite)
    {
        //Start parsing the file
        $file = fopen($roomCSV, "r");
        
        $column_headers = array();
        $parsedRoom_csv = array();
        $row_count = 0;
        while (!feof($file)) 
        {
            $row = fgetcsv($file, 1000);
                
            if ($row_count==0)
            {
                $column_headers = $row;
                $row_count += 1;
                continue;
            }

            $parsedRoom_csv[$row[0]] = $row[4];

            $row_count += 1; 
        }

        fclose($file);

        $titles = [];
        $events = [];

        //add the headers
        $event = array();
        $event['Room'] = 'Room';
        $event['Capacity'] = 'Capacity';
        $event['UUID'] = 'UUID';
        $event['Professor'] = 'Professor';
        $event['Event'] = 'Class';
        $event['Title'] = 'Title';
        $event['Type'] = 'Type';
        $event['Time']  = 'Time';
        $event['Length'] = 'Length';
        $events[] = $event;

        $professorsFakeID = [];

        $fakeUID = 1523543;

        //Start parsing the file
        $csvFile = fopen($CSV, "r");
        $column_headers = array();
        $row_count = 0;
        $current_schedule = "";
        $previous_schedule = "";
        while (!feof($csvFile)) 
        {
            $row = fgetcsv($csvFile, 1000);
                
            if ($row_count==0)
            {
                $column_headers = $row;
                $row_count += 1;
                continue;
            }

            $current_schedule = $row[1] . "_" . $row[0];

            if($current_schedule !== $previous_schedule && $previous_schedule != "")
            {
                $path = $pathToWrite . "\\" . $previous_schedule;
                DataConvertUtils::write_file($path, $events);
                $events = [];

                //add the headers
                $event = array();
                $event['Room'] = 'Room';
                $event['Capacity'] = 'Capacity';
                $event['UUID'] = 'UUID';
                $event['Professor'] = 'Professor';
                $event['Event'] = 'Class';
                $event['Title'] = 'Title';
                $event['Type'] = 'Type';
                $event['Time']  = 'Time';
                $event['Length'] = 'Length';
                $events[] = $event;
            }

            if(!isset($professorsFakeID[$row[16]]))
            {
                $professorsFakeID[$row[16]] = $fakeUID;
                $fakeUID++;
            }

            if(strlen(($row[15])) < 3)
            {
                continue;
            }

            $days = "";
            foreach (explode(',', $row[10]) as $value) 
            {
                if(strlen($days) > 0)
                {
                    $days .= '|';
                }

                $days .= $value . $row[12];
            }            

            $event = array();
            $event['Room'] = $row[15];
            $event['Capacity'] = isset($parsedRoom_csv[$row[15]]) ? $parsedRoom_csv[$row[15]] : 0;
            $event['UUID']  = 'U' . $professorsFakeID[$row[16]];   //fake
            $event['Professor'] = $row[16];
            $event['Event'] = $row[2] . " " . $row[3];
            $event['Title'] = $row[9];
            $event['Type']  = $row[5];
            $event['Time'] = $days;
            $event['Length'] = $row[14];
            $events[] = $event;

            $previous_schedule = $current_schedule;
        }

        $path = $pathToWrite . "\\" . $current_schedule;
        DataConvertUtils::write_file($path, $events);

        fclose($csvFile);
    }

    public static function write_file($toWrite, $events)
    {
        $toWrite = $toWrite .".csv";
        $fp = fopen($toWrite, 'w');

        foreach($events as $event)
        {  
            fputcsv($fp, $event);
        }
        
        fclose($fp);
    }
}