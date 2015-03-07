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
                
            if ($row_count==0)
            {
                $column_headers = $row;
                
                if(!DataConvertUtils::verifyHeaders($column_headers, 'full'))
                {
                    throw new exception("Error: invalid headers");
                }

                $row_count += 1;
                continue;
            }

            //if the row doesn't have enough columns skip it
            //eventually use this as part of the % bad data
            if(count($row) < 8)
            {
                continue;
            }

            //check if the data is in the correct format using regex and other such methods

            $room = Room::firstOrCreate(
            array(
                'name' => $row[0],
                'schedule_id' => $schedule->id,
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

                
            $days = '';
            $time = '';

            foreach (explode('|', $row[6]) as $value) 
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
                   'length' => $row[7],
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
                'class_type' => '',
                'title' => $row[5],
                'etime_id' => $timeblock ? $timeblock->id : 1
            ));

            $row_count += 1; 
        }

        fclose($file);

        return $failures;
    }

    private static function verifyHeaders($headers, $mode)
    {
        $verify = true;
        if($mode == 'full' && count($headers) < 8)
        {
            return false;
        }
        else
        {
            $verify = $verify && (strcasecmp($headers[0], 'Room') == 0); 
            $verify = $verify && (strcasecmp($headers[1], 'Capacity') == 0); 
            $verify = $verify && (strcasecmp($headers[2], 'UUID') == 0); 
            $verify = $verify && (strcasecmp($headers[3], 'Professor') == 0); 
            $verify = $verify && (strcasecmp($headers[4], 'Class') == 0); 
            $verify = $verify && (strcasecmp($headers[5], 'Title') == 0); 
            $verify = $verify && (strcasecmp($headers[6], 'Time') == 0); 
            $verify = $verify && (strcasecmp($headers[7], 'Length') == 0); 
        }
        //additional modes can be added

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
        $event['Time']  = 'Time';
        $event['Length'] = 'Length';
        $events[] = $event;

        $fakeUID = 0523543;

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
}