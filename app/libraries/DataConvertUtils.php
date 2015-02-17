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

        $events = [];

        foreach ($json as $value) 
        {
            if(isset($events[$value->name]))
            {
                $events[$value->name]['Time'] .= "|". DataConvertUtils::convertIntToStringDay($value->day) . $value->starttm;
            }
            else
            {
                $event = array();
                $event['Room'] = $value->room;
                $event['Capacity'] = isset($parsedRoom_csv[$value->room]) ? $parsedRoom_csv[$value->room] : 0;
                $event['Professor'] = 'Jim';
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