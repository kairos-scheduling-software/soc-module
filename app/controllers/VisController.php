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
    public function getData($id0, $id1, $id2 = 0)
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
    x.id,
    x.starttm,
    x.length,
    concat('[', replace(x.days, '|', ','), ']') as days,
    x.name,
    x.class_type,
    x.title,
    x.meets_with,
    x.room,
    x.main_prof,
    x.num_classes
from
(
    select
        e.id,
        et.starttm,
        et.`length`,
        et.days,
        e.name,
        e.class_type,
        e.title,
        group_concat(concat(e.name, ' (', e.title, ')') ORDER BY e.name, e.title SEPARATOR '^') as meets_with,
        r.`name` as room,
        p.`name` as main_prof,
        count(e.id) as num_classes
    from events e
    left join etimes et
        on(e.etime_id = et.id)
    left join rooms r
        on(e.room_id = r.id)
    left join professors p
        on(e.professor = p.id)
    where e.schedule_id = $id0
    group by 
        et.starttm,
        et.`length`,
        et.days,
        r.`name`
) x;    
SQL;
            } else if($id1 == 3) {
                // Diff view
$sql = <<<SQL
select
    z.id,
    et.starttm,
    et.`length`,
    concat('[', replace(et.days, '|', ','), ']') as days,
    z.name,
    z.class_type,
    z.title,
    r.`name` as room,
    p.`name` as main_prof,
    z.diff,
    z.meets_with,
    z.num_classes
from
(
    select
        x.id,
        x.etime_id,
        x.name,
        x.class_type,
        x.title,
        group_concat(concat(x.name, ' (', x.title, ')') ORDER BY x.name, x.title SEPARATOR '^') as meets_with,
        x.room_id,
        x.professor,
        count(x.id) as num_classes,
        max(x.diff) as diff -- this works since we are using e and p
    from
    (
        select
            e1.id,
            e1.etime_id,
            e1.name,
            e1.class_type,
            e1.title,
            e1.room_id,
            e1.professor,
            if(e2.id, 'e', 'p') as diff
        from events e1
        left outer join events e2
            on
            (
                e1.etime_id = e2.etime_id 
                and e1.name = e2.name 
                and e1.class_type = e2.class_type
                and e1.room_id = e2.room_id
                and e2.schedule_id = $id2
            )
        where 
            e1.schedule_id = $id0
    ) x
    group by 
        x.etime_id,
        x.room_id

    union all 

    select
        x.id,
        x.etime_id,
        x.name,
        x.class_type,
        x.title,
        group_concat(concat(x.name, ' (', x.title, ')') ORDER BY x.name, x.title SEPARATOR '^') as meets_with,
        x.room_id,
        x.professor,
        count(x.id) as num_classes,
        max(x.diff) as diff
    from
    (
        select
            e1.id,
            e1.etime_id,
            e1.name,
            e1.class_type,
            e1.title,
            e1.room_id,
            e1.professor,
            'm' as diff
        from events e1
        left outer join events e2
            on
            (
                e1.etime_id = e2.etime_id 
                and e1.name = e2.name 
                and e1.class_type = e2.class_type
                and e1.room_id = e2.room_id
                and e2.schedule_id = $id0
            )
        where 
            e1.schedule_id = $id2
            and e2.schedule_id is null
    ) x
    group by 
        x.etime_id,
        x.room_id
) z
left join etimes et
    on(z.etime_id = et.id)
left join rooms r
    on(z.room_id = r.id)
left join professors p
    on(z.professor = p.id);
SQL;
            } else {
                return '{}';
            }

            $results = DB::select($sql, array());
            return $results;
        }
}

