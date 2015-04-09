<?php

class VisController extends \BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {

$sql = <<<SQL
select
    s.id, s.`name`, s.year, s.semester
from schedules s
where s.final = true
    order by s.year desc, s.semester;
SQL;

            $results = DB::select($sql, array());
            return $results;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id0    the schedule_id
     * @param  int  $id1    visualization type
     * @return Response
     */
    public function getData($id0, $id1)
    {
            $sql = "";
            if($id1 == 0) {
                // Class counts view
$sql = <<<SQL
select
    count(*) as Count,
    s.semester as Semester,
    s.year as Year,
    s.id
from schedules s
join events e
    on(s.id = e.schedule_id)
where s.final = true
group by
    s.semester,
    s.year,
    s.id
order by 
    s.year,
    s.semester,
    s.id;
SQL;
            } else if($id1 == 1) {
                // Prof counts
$sql = <<<SQL
select
    count(*) as Total,
    s.`name`,
    e.class_type as Type,
    p.name as Professor
from schedules s
join events e
    on(s.id = e.schedule_id)
left join professors p
    on(e.professor = p.id)
where s.id = $id0
    -- and s.final = true -- Not needed here since we already did this in the first query
group by
    e.class_type,
    p.name
order by 
    e.class_type,
    p.name;
SQL;
            } else if($id1 == 2) {
                // Main schedule view
$sql = <<<SQL
select
    et.starttm,
    et.`length`,
    concat('[', replace(et.days, '|', ','), ']') as days,
    e.name,
    e.class_type,
    e.title,
    r.`name` as room,
    p.`name` as main_prof
from schedules s
join events e
    on(s.id = e.schedule_id)
left join etimes et
    on(e.etime_id = et.id)
left join rooms r
    on(e.room_id = r.id)
left join professors p
    on(e.professor = p.id)
where s.id = $id0;
SQL;
            } else {
                return '{}';
            }

            $results = DB::select($sql, array());
            return $results;
        }
}

