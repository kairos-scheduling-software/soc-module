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
    s.id, s.`name`
from schedules s;
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
    p.`name` as professor
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
            } else if($id1 == 1) {
                // Class counts view
$sql = <<<SQL
select
    a.Semester,
    a.Year,
    count(*) as Count
from
(
    select
        substr(s.`name`, 1, instr(s.`name`, ' ') - 1) as Semester,
        substr(s.`name`, instr(s.`name`, ' ') + 1) as Year,
        s.`name`
    from schedules s
    join events e
        on(s.id = e.schedule_id)
) a
group by
    a.Semester,
    a.Year;
SQL;
            } else if($id1 == 2) {
                // Heatmaps
$sql = <<<SQL

SQL;
            } else {
                return '{}';
            }

            $results = DB::select($sql, array());
            return $results;
        }
}

