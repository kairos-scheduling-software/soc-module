<?php

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

		$this->call('TableSeeder');
	}

}

/**
* 
*/
class TableSeeder extends Seeder
{
	
	public function run()
	{
		DB::table('users')->delete();
		$user = User::create(
			array(
				'username' => 'admin',
				'email' => 'foo@bar.com',
				'first' => 'admin',
				'last' => 'admin',
				'password' => Hash::make('admin'),
				'temp_password' => '',
				'activation_code' => '',
				'active' => 1,
				'remember_token' => '' 
		));

		DB::table('schedules')->delete();
		$schedule = Schedule::create(
			array(
				'name' => 'Spring 2013',
				'json_schedule' => '',
				'description' => '',
				'last_edited_by' => $user->id
		));

		DB::table('schedule_user')->delete();

		$user->schedules()->attach($schedule);

		$schedule = Schedule::create(
			array(
				'name' => 'Fall 2013',
				'json_schedule' => '',
				'description' => '',
				'last_edited_by' => $user->id
		));

		$user->schedules()->attach($schedule);

		$schedule = Schedule::create(
			array(
				'name' => 'Spring 2014',
				'json_schedule' => 
				'[
    {
        "starttm": "0835",
        "length": 50,
        "day": 2,
        "name": "CS 1410-007",
        "class_type": "Laboratory",
        "title": "Object-Oriented Prog",
        "room": "MEB 3225"
    },
    {
        "starttm": "0835",
        "length": 50,
        "day": 3,
        "name": "CS 3500-002",
        "class_type": "Discussion",
        "title": "Software Practice",
        "room": "MEB 3225"
    },
    {
        "starttm": "0835",
        "length": 50,
        "day": 5,
        "name": "CS 1410-035",
        "class_type": "Laboratory",
        "title": "Object-Oriented Prog",
        "room": "WEB L124"
    },
    {
        "starttm": "0835",
        "length": 50,
        "day": 5,
        "name": "CS 1410-045",
        "class_type": "Laboratory",
        "title": "Object-Oriented Prog",
        "room": "WEB L124"
    },
    {
        "starttm": "0910",
        "length": 80,
        "day": 2,
        "name": "CS 3810-001",
        "class_type": "Lecture",
        "title": "Computer Organization",
        "room": "WEB L104"
    },
    {
        "starttm": "0910",
        "length": 80,
        "day": 2,
        "name": "CS 5630-001",
        "class_type": "Lecture",
        "title": "Visualization",
        "room": "WEB L102"
    },
    {
        "starttm": "0910",
        "length": 80,
        "day": 2,
        "name": "CS 6630-001",
        "class_type": "Lecture",
        "title": "Visualization",
        "room": "WEB L102"
    },
    {
        "starttm": "0910",
        "length": 80,
        "day": 4,
        "name": "CS 3810-001",
        "class_type": "Lecture",
        "title": "Computer Organization",
        "room": "WEB L104"
    },
    {
        "starttm": "0910",
        "length": 80,
        "day": 4,
        "name": "CS 5630-001",
        "class_type": "Lecture",
        "title": "Visualization",
        "room": "WEB L102"
    },
    {
        "starttm": "0910",
        "length": 80,
        "day": 4,
        "name": "CS 6630-001",
        "class_type": "Lecture",
        "title": "Visualization",
        "room": "WEB L102"
    },
    {
        "starttm": "0940",
        "length": 50,
        "day": 1,
        "name": "CS 1400-008",
        "class_type": "Laboratory",
        "title": "Introduction to CS",
        "room": "WEB L124"
    },
    {
        "starttm": "0940",
        "length": 50,
        "day": 2,
        "name": "CS 1410-002",
        "class_type": "Laboratory",
        "title": "Object-Oriented Prog",
        "room": "MEB 3225"
    },
    {
        "starttm": "0940",
        "length": 50,
        "day": 3,
        "name": "CS 1400-008",
        "class_type": "Laboratory",
        "title": "Introduction to CS",
        "room": "WEB L124"
    },
    {
        "starttm": "0940",
        "length": 50,
        "day": 3,
        "name": "CS 3500-003",
        "class_type": "Discussion",
        "title": "Software Practice",
        "room": "MEB 3225"
    },
    {
        "starttm": "0940",
        "length": 50,
        "day": 5,
        "name": "CS 1410-031",
        "class_type": "Laboratory",
        "title": "Object-Oriented Prog",
        "room": "WEB L124"
    },
    {
        "starttm": "0940",
        "length": 50,
        "day": 5,
        "name": "CS 1410-041",
        "class_type": "Laboratory",
        "title": "Object-Oriented Prog",
        "room": "WEB L124"
    },
    {
        "starttm": "0940",
        "length": 50,
        "day": 5,
        "name": "CS 2420-002",
        "class_type": "Laboratory",
        "title": "Intro Alg & Data Struct",
        "room": "MEB 3225"
    },
    {
        "starttm": "1045",
        "length": 50,
        "day": 1,
        "name": "CS 1400-007",
        "class_type": "Laboratory",
        "title": "Introduction to CS",
        "room": "WEB L124"
    },
    {
        "starttm": "1045",
        "length": 50,
        "day": 1,
        "name": "CS 1410-001",
        "class_type": "Lecture",
        "title": "Object-Oriented Prog",
        "room": "WEB L101"
    },
    {
        "starttm": "1045",
        "length": 50,
        "day": 2,
        "name": "CS 1410-003",
        "class_type": "Laboratory",
        "title": "Object-Oriented Prog",
        "room": "MEB 3225"
    },
    {
        "starttm": "1045",
        "length": 80,
        "day": 2,
        "name": "CS 5300-001",
        "class_type": "Lecture",
        "title": "Artificial Intelligence",
        "room": "WEB 2250"
    },
    {
        "starttm": "1045",
        "length": 50,
        "day": 3,
        "name": "CS 1400-007",
        "class_type": "Laboratory",
        "title": "Introduction to CS",
        "room": "WEB L124"
    },
    {
        "starttm": "1045",
        "length": 50,
        "day": 3,
        "name": "CS 1410-001",
        "class_type": "Lecture",
        "title": "Object-Oriented Prog",
        "room": "WEB L101"
    },
    {
        "starttm": "1045",
        "length": 50,
        "day": 3,
        "name": "CS 3500-004",
        "class_type": "Discussion",
        "title": "Software Practice",
        "room": "MEB 3225"
    },
    {
        "starttm": "1045",
        "length": 50,
        "day": 3,
        "name": "CS 4000-001",
        "class_type": "Lecture",
        "title": "Senior Capstone Design",
        "room": "WEB 2230"
    },
    {
        "starttm": "1045",
        "length": 80,
        "day": 4,
        "name": "CS 3100-001",
        "class_type": "Lecture",
        "title": "Models Of Computation",
        "room": "WEB 1230"
    },
    {
        "starttm": "1045",
        "length": 80,
        "day": 4,
        "name": "CS 5300-001",
        "class_type": "Lecture",
        "title": "Artificial Intelligence",
        "room": "WEB 2250"
    },
    {
        "starttm": "1045",
        "length": 50,
        "day": 5,
        "name": "CS 1410-001",
        "class_type": "Lecture",
        "title": "Object-Oriented Prog",
        "room": "WEB L101"
    },
    {
        "starttm": "1045",
        "length": 50,
        "day": 5,
        "name": "CS 1410-032",
        "class_type": "Laboratory",
        "title": "Object-Oriented Prog",
        "room": "WEB L124"
    },
    {
        "starttm": "1045",
        "length": 50,
        "day": 5,
        "name": "CS 1410-042",
        "class_type": "Laboratory",
        "title": "Object-Oriented Prog",
        "room": "WEB L124"
    },
    {
        "starttm": "1045",
        "length": 50,
        "day": 5,
        "name": "CS 2420-003",
        "class_type": "Laboratory",
        "title": "Intro Alg & Data Struct",
        "room": "MEB 3225"
    },
    {
        "starttm": "1045",
        "length": 50,
        "day": 5,
        "name": "CS 4000-001",
        "class_type": "Lecture",
        "title": "Senior Capstone Design",
        "room": "WEB 2230"
    },
    {
        "starttm": "1150",
        "length": 50,
        "day": 1,
        "name": "CS 1400-006",
        "class_type": "Laboratory",
        "title": "Introduction to CS",
        "room": "WEB L124"
    },
    {
        "starttm": "1150",
        "length": 80,
        "day": 1,
        "name": "CS 4600-001",
        "class_type": "Lecture",
        "title": "Computer Graphics",
        "room": "WEB 1230"
    },
    {
        "starttm": "1150",
        "length": 80,
        "day": 1,
        "name": "CS 5785-001",
        "class_type": "Lecture",
        "title": "Adv. Embedded Software",
        "room": "MEB 3105"
    },
    {
        "starttm": "1150",
        "length": 80,
        "day": 1,
        "name": "CS 6600-001",
        "class_type": "Lecture",
        "title": "Math of Comp Graphics",
        "room": "MEB 3147"
    },
    {
        "starttm": "1150",
        "length": 80,
        "day": 1,
        "name": "CS 6785-001",
        "class_type": "Lecture",
        "title": "Adv. Embedded Software",
        "room": "MEB 3105"
    },
    {
        "starttm": "1150",
        "length": 50,
        "day": 2,
        "name": "CS 1410-004",
        "class_type": "Laboratory",
        "title": "Object-Oriented Prog",
        "room": "MEB 3225"
    },
    {
        "starttm": "1150",
        "length": 50,
        "day": 3,
        "name": "CS 1400-006",
        "class_type": "Laboratory",
        "title": "Introduction to CS",
        "room": "WEB L124"
    },
    {
        "starttm": "1150",
        "length": 50,
        "day": 3,
        "name": "CS 3500-005",
        "class_type": "Discussion",
        "title": "Software Practice",
        "room": "MEB 3225"
    },
    {
        "starttm": "1150",
        "length": 80,
        "day": 3,
        "name": "CS 4600-001",
        "class_type": "Lecture",
        "title": "Computer Graphics",
        "room": "WEB 1230"
    },
    {
        "starttm": "1150",
        "length": 80,
        "day": 3,
        "name": "CS 5785-001",
        "class_type": "Lecture",
        "title": "Adv. Embedded Software",
        "room": "MEB 3105"
    },
    {
        "starttm": "1150",
        "length": 80,
        "day": 3,
        "name": "CS 6600-001",
        "class_type": "Lecture",
        "title": "Math of Comp Graphics",
        "room": "MEB 3147"
    },
    {
        "starttm": "1150",
        "length": 80,
        "day": 3,
        "name": "CS 6785-001",
        "class_type": "Lecture",
        "title": "Adv. Embedded Software",
        "room": "MEB 3105"
    },
    {
        "starttm": "1150",
        "length": 80,
        "day": 3,
        "name": "CS 7931-001",
        "class_type": "Seminar",
        "title": "Data Mining Seminar",
        "room": "WEB 1450"
    },
    {
        "starttm": "1150",
        "length": 50,
        "day": 5,
        "name": "CS 1410-033",
        "class_type": "Laboratory",
        "title": "Object-Oriented Prog",
        "room": "WEB L124"
    },
    {
        "starttm": "1150",
        "length": 50,
        "day": 5,
        "name": "CS 1410-043",
        "class_type": "Laboratory",
        "title": "Object-Oriented Prog",
        "room": "WEB L124"
    },
    {
        "starttm": "1150",
        "length": 50,
        "day": 5,
        "name": "CS 2420-004",
        "class_type": "Laboratory",
        "title": "Intro Alg & Data Struct",
        "room": "MEB 3225"
    },
    {
        "starttm": "1200",
        "length": 60,
        "day": 1,
        "name": "CS 7938-001",
        "class_type": "Seminar",
        "title": "Image Analysis Seminar",
        "room": "WEB 3760"
    },
    {
        "starttm": "1225",
        "length": 80,
        "day": 2,
        "name": "CS 2100-001",
        "class_type": "Lecture",
        "title": "Discrete Structures",
        "room": "WEB L101"
    },
    {
        "starttm": "1225",
        "length": 80,
        "day": 2,
        "name": "CS 3020-001",
        "class_type": "Seminar",
        "title": "Research Forum",
        "room": "WEB 1250"
    },
    {
        "starttm": "1225",
        "length": 80,
        "day": 2,
        "name": "CS 4960-001",
        "class_type": "Special Topics",
        "title": "Comp Mthds for Sim & Data Sci",
        "room": "WEB 2250"
    },
    {
        "starttm": "1225",
        "length": 80,
        "day": 2,
        "name": "CS 6210-001",
        "class_type": "Lecture",
        "title": "Adv Sci Computing I",
        "room": "WEB 1230"
    },
    {
        "starttm": "1225",
        "length": 80,
        "day": 4,
        "name": "CS 2100-001",
        "class_type": "Lecture",
        "title": "Discrete Structures",
        "room": "WEB L101"
    },
    {
        "starttm": "1225",
        "length": 80,
        "day": 4,
        "name": "CS 4960-001",
        "class_type": "Special Topics",
        "title": "Comp Mthds for Sim & Data Sci",
        "room": "WEB 2250"
    },
    {
        "starttm": "1225",
        "length": 80,
        "day": 4,
        "name": "CS 6210-001",
        "class_type": "Lecture",
        "title": "Adv Sci Computing I",
        "room": "WEB 1230"
    },
    {
        "starttm": "1255",
        "length": 50,
        "day": 1,
        "name": "CS 1400-002",
        "class_type": "Laboratory",
        "title": "Introduction to CS",
        "room": "WEB L124"
    },
    {
        "starttm": "1255",
        "length": 50,
        "day": 2,
        "name": "CS 1410-005",
        "class_type": "Laboratory",
        "title": "Object-Oriented Prog",
        "room": "MEB 3225"
    },
    {
        "starttm": "1255",
        "length": 50,
        "day": 3,
        "name": "CS 1400-002",
        "class_type": "Laboratory",
        "title": "Introduction to CS",
        "room": "WEB L124"
    },
    {
        "starttm": "1255",
        "length": 50,
        "day": 3,
        "name": "CS 3500-006",
        "class_type": "Discussion",
        "title": "Software Practice",
        "room": "MEB 3225"
    },
    {
        "starttm": "1255",
        "length": 50,
        "day": 4,
        "name": "CS 7939-001",
        "class_type": "Seminar",
        "title": "Robotics",
        "room": "MEB 3147"
    },
    {
        "starttm": "1255",
        "length": 50,
        "day": 5,
        "name": "CS 1410-034",
        "class_type": "Laboratory",
        "title": "Object-Oriented Prog",
        "room": "WEB L124"
    },
    {
        "starttm": "1255",
        "length": 50,
        "day": 5,
        "name": "CS 1410-044",
        "class_type": "Laboratory",
        "title": "Object-Oriented Prog",
        "room": "WEB L124"
    },
    {
        "starttm": "1255",
        "length": 50,
        "day": 5,
        "name": "CS 2420-005",
        "class_type": "Laboratory",
        "title": "Intro Alg & Data Struct",
        "room": "MEB 3225"
    },
    {
        "starttm": "1310",
        "length": 100,
        "day": 3,
        "name": "CS 6960-001",
        "class_type": "Special Topics",
        "title": "Wrtg and Comm for Grad Stdnts",
        "room": "WEB 2250"
    },
    {
        "starttm": "1325",
        "length": 80,
        "day": 1,
        "name": "CS 4400-001",
        "class_type": "Lecture",
        "title": "Computer Systems",
        "room": "WEB L101"
    },
    {
        "starttm": "1325",
        "length": 80,
        "day": 1,
        "name": "CS 5490-001",
        "class_type": "Lecture",
        "title": "Network Security",
        "room": "WEB L102"
    },
    {
        "starttm": "1325",
        "length": 80,
        "day": 1,
        "name": "CS 6490-001",
        "class_type": "Lecture",
        "title": "Network Security",
        "room": "WEB L102"
    },
    {
        "starttm": "1325",
        "length": 80,
        "day": 1,
        "name": "CS 6640-001",
        "class_type": "Lecture",
        "title": "Image Processing",
        "room": "WEB 1230"
    },
    {
        "starttm": "1325",
        "length": 80,
        "day": 1,
        "name": "CS 6665-001",
        "class_type": "Lecture",
        "title": "Character Animation",
        "room": "WEB 1450"
    },
    {
        "starttm": "1325",
        "length": 80,
        "day": 3,
        "name": "CS 4400-001",
        "class_type": "Lecture",
        "title": "Computer Systems",
        "room": "WEB L101"
    },
    {
        "starttm": "1325",
        "length": 80,
        "day": 3,
        "name": "CS 5490-001",
        "class_type": "Lecture",
        "title": "Network Security",
        "room": "WEB L102"
    },
    {
        "starttm": "1325",
        "length": 80,
        "day": 3,
        "name": "CS 6490-001",
        "class_type": "Lecture",
        "title": "Network Security",
        "room": "WEB L102"
    },
    {
        "starttm": "1325",
        "length": 80,
        "day": 3,
        "name": "CS 6640-001",
        "class_type": "Lecture",
        "title": "Image Processing",
        "room": "WEB 1230"
    },
    {
        "starttm": "1325",
        "length": 80,
        "day": 3,
        "name": "CS 6665-001",
        "class_type": "Lecture",
        "title": "Character Animation",
        "room": "WEB 1450"
    },
    {
        "starttm": "1345",
        "length": 75,
        "day": 4,
        "name": "CS 7941-001",
        "class_type": "Seminar",
        "title": "Advanced Seminar",
        "room": "MEB 3147"
    },
    {
        "starttm": "1400",
        "length": 50,
        "day": 1,
        "name": "CS 1400-003",
        "class_type": "Laboratory",
        "title": "Introduction to CS",
        "room": "WEB L124"
    },
    {
        "starttm": "1400",
        "length": 50,
        "day": 1,
        "name": "CS 5310-001",
        "class_type": "Lecture",
        "title": "Robotics",
        "room": "WEB 1250"
    },
    {
        "starttm": "1400",
        "length": 50,
        "day": 1,
        "name": "CS 6310-001",
        "class_type": "Lecture",
        "title": "Robotics",
        "room": "WEB 1250"
    },
    {
        "starttm": "1400",
        "length": 50,
        "day": 2,
        "name": "CS 1400-001",
        "class_type": "Lecture",
        "title": "Introduction to CS",
        "room": "WEB L101"
    },
    {
        "starttm": "1400",
        "length": 50,
        "day": 2,
        "name": "CS 1410-006",
        "class_type": "Laboratory",
        "title": "Object-Oriented Prog",
        "room": "MEB 3225"
    },
    {
        "starttm": "1400",
        "length": 80,
        "day": 2,
        "name": "CS 1410-030",
        "class_type": "Lecture",
        "title": "Object-Oriented Prog",
        "room": "WEB L103"
    },
    {
        "starttm": "1400",
        "length": 80,
        "day": 2,
        "name": "CS 1410-040",
        "class_type": "Lecture",
        "title": "Object-Oriented Prog",
        "room": "WEB L103"
    },
    {
        "starttm": "1400",
        "length": 80,
        "day": 2,
        "name": "CS 3500-001",
        "class_type": "Lecture",
        "title": "Software Practice",
        "room": "WEB L104"
    },
    {
        "starttm": "1400",
        "length": 50,
        "day": 3,
        "name": "CS 1400-003",
        "class_type": "Laboratory",
        "title": "Introduction to CS",
        "room": "WEB L124"
    },
    {
        "starttm": "1400",
        "length": 50,
        "day": 3,
        "name": "CS 3500-007",
        "class_type": "Discussion",
        "title": "Software Practice",
        "room": "MEB 3225"
    },
    {
        "starttm": "1400",
        "length": 50,
        "day": 3,
        "name": "CS 5310-001",
        "class_type": "Lecture",
        "title": "Robotics",
        "room": "WEB 1250"
    },
    {
        "starttm": "1400",
        "length": 50,
        "day": 3,
        "name": "CS 6310-001",
        "class_type": "Lecture",
        "title": "Robotics",
        "room": "WEB 1250"
    },
    {
        "starttm": "1400",
        "length": 50,
        "day": 4,
        "name": "CS 1400-001",
        "class_type": "Lecture",
        "title": "Introduction to CS",
        "room": "WEB L101"
    },
    {
        "starttm": "1400",
        "length": 80,
        "day": 4,
        "name": "CS 1410-030",
        "class_type": "Lecture",
        "title": "Object-Oriented Prog",
        "room": "WEB L103"
    },
    {
        "starttm": "1400",
        "length": 80,
        "day": 4,
        "name": "CS 1410-040",
        "class_type": "Lecture",
        "title": "Object-Oriented Prog",
        "room": "WEB L103"
    },
    {
        "starttm": "1400",
        "length": 80,
        "day": 4,
        "name": "CS 3500-001",
        "class_type": "Lecture",
        "title": "Software Practice",
        "room": "WEB L104"
    },
    {
        "starttm": "1400",
        "length": 50,
        "day": 5,
        "name": "CS 5310-001",
        "class_type": "Lecture",
        "title": "Robotics",
        "room": "WEB 1250"
    },
    {
        "starttm": "1400",
        "length": 50,
        "day": 5,
        "name": "CS 6310-001",
        "class_type": "Lecture",
        "title": "Robotics",
        "room": "WEB 1250"
    },
    {
        "starttm": "1400",
        "length": 50,
        "day": 5,
        "name": "CS 7932-001",
        "class_type": "Seminar",
        "title": "Scientific Computing & Imaging",
        "room": "WEB 3760"
    },
    {
        "starttm": "1500",
        "length": 80,
        "day": 1,
        "name": "CS 2420-001",
        "class_type": "Lecture",
        "title": "Intro Alg & Data Struct",
        "room": "WEB L104"
    },
    {
        "starttm": "1500",
        "length": 80,
        "day": 3,
        "name": "CS 2420-001",
        "class_type": "Lecture",
        "title": "Intro Alg & Data Struct",
        "room": "WEB L104"
    },
    {
        "starttm": "1505",
        "length": 50,
        "day": 1,
        "name": "CS 1400-004",
        "class_type": "Laboratory",
        "title": "Introduction to CS",
        "room": "WEB L124"
    },
    {
        "starttm": "1505",
        "length": 150,
        "day": 1,
        "name": "CS 4190-001",
        "class_type": "Seminar",
        "title": "Programming Challenges",
        "room": "MEB 3225"
    },
    {
        "starttm": "1505",
        "length": 50,
        "day": 3,
        "name": "CS 1400-004",
        "class_type": "Laboratory",
        "title": "Introduction to CS",
        "room": "WEB L124"
    },
    {
        "starttm": "1505",
        "length": 50,
        "day": 3,
        "name": "CS 3500-008",
        "class_type": "Discussion",
        "title": "Software Practice",
        "room": "MEB 3225"
    },
    {
        "starttm": "1505",
        "length": 150,
        "day": 5,
        "name": "CS 4190-001",
        "class_type": "Seminar",
        "title": "Programming Challenges",
        "room": "MEB 3225"
    },
    {
        "starttm": "1540",
        "length": 80,
        "day": 2,
        "name": "CS 3130-001",
        "class_type": "Lecture",
        "title": "Eng Prob Stats",
        "room": "WEB 2230"
    },
    {
        "starttm": "1540",
        "length": 80,
        "day": 2,
        "name": "CS 6620-001",
        "class_type": "Lecture",
        "title": "Ray Tracing for Graphic",
        "room": "WEB 2250"
    },
    {
        "starttm": "1540",
        "length": 80,
        "day": 4,
        "name": "CS 3130-001",
        "class_type": "Lecture",
        "title": "Eng Prob Stats",
        "room": "WEB 2230"
    },
    {
        "starttm": "1540",
        "length": 80,
        "day": 4,
        "name": "CS 6620-001",
        "class_type": "Lecture",
        "title": "Ray Tracing for Graphic",
        "room": "WEB 2250"
    },
    {
        "starttm": "1610",
        "length": 50,
        "day": 1,
        "name": "CS 1400-005",
        "class_type": "Laboratory",
        "title": "Introduction to CS",
        "room": "WEB L124"
    },
    {
        "starttm": "1610",
        "length": 50,
        "day": 3,
        "name": "CS 1400-005",
        "class_type": "Laboratory",
        "title": "Introduction to CS",
        "room": "WEB L124"
    },
    {
        "starttm": "1630",
        "length": 60,
        "day": 1,
        "name": "CS 6955-001",
        "class_type": "Special Topics",
        "title": "GRFP Proposal Writing",
        "room": "MEB 3147"
    },
    {
        "starttm": "1630",
        "length": 60,
        "day": 3,
        "name": "CS 6955-001",
        "class_type": "Special Topics",
        "title": "GRFP Proposal Writing",
        "room": "MEB 3147"
    },
    {
        "starttm": "1715",
        "length": 80,
        "day": 2,
        "name": "CS 5710-001",
        "class_type": "Lecture",
        "title": "Digital VLSI Design",
        "room": "WEB 2230"
    },
    {
        "starttm": "1715",
        "length": 80,
        "day": 2,
        "name": "CS 6710-001",
        "class_type": "Lecture",
        "title": "Digital VLSI Design",
        "room": "WEB 2230"
    },
    {
        "starttm": "1715",
        "length": 80,
        "day": 4,
        "name": "CS 5710-001",
        "class_type": "Lecture",
        "title": "Digital VLSI Design",
        "room": "WEB 2230"
    },
    {
        "starttm": "1715",
        "length": 80,
        "day": 4,
        "name": "CS 6710-001",
        "class_type": "Lecture",
        "title": "Digital VLSI Design",
        "room": "WEB 2230"
    }
]',
				'description' => '',
				'last_edited_by' => $user->id
		));

		$user->schedules()->attach($schedule);

		DB::table('professors')->delete();

		$professor = Professor::create(
			array(
				'name' => 'Unassigned'
			));

		DB::table('rooms')->delete();
		$room = Room::create(
			array(
				'name' => 'Unassigned',
				'capacity' => 0,
				'availability' => '',
				'schedule_id' => $schedule->id,
		));

		DB::table('events')->delete();
		DB::table('constraints')->delete();
		$event = models\Event::create(
			 array(
			 	"name" => "CS 1410-007",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 3500-002",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 1410-035",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 1410-045",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 3810-001",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 5630-001",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 6630-001",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 3810-001",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 5630-001",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 6630-001",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 1400-008",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 1410-002",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 3500-003",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 1410-031",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 1410-041",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 2420-002",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		
		$event = models\Event::create(
			 array(
			 	"name" => "CS 1400-007",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 1410-001",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 1410-003",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 5300-001",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 1400-007",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 3500-004",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 4000-001",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 3100-001",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 5300-001",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 1410-032",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));

		$event = models\Event::create(
			 array(
			 	"name" => "CS 1410-042",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 2420-003",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 4000-001",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 1400-006",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 4600-001",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 5785-001",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 6600-001",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 6785-001",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 1410-004",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 1400-006",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 3500-005",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 4600-001",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 5785-001",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 6600-001",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 6785-001",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 7931-001",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 1410-033",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 1410-043",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 2420-004",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 7938-001",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 2100-001",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 3020-001",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 4960-001",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 6210-001",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 2100-001",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 4960-001",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 6210-001",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 1400-002",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 1410-005",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 1400-002",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 3500-006",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 7939-001",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 1410-034",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 1410-044",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 2420-005",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 6960-001",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 4400-001",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));

		$constraint = Constraint::create(
			array(
				'key' => 'MustBeAT',
				'value' => '1:25',
				'event_id' => $event->id,
				'type' => 'hard'
		));

		$event = models\Event::create(
			 array(
			 	"name" => "CS AwesomeFunClass-001",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 5490-001",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 6490-001",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 6640-001",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 6665-001",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 5490-001",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		
		$event = models\Event::create(
			 array(
			 	"name" => "CS 7941-001",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 1400-003",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 5310-001",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 6310-001",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 1400-001",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 1410-006",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 1410-030",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 1410-040",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 3500-001",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 1400-003",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 3500-007",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 5310-001",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 6310-001",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 1400-001",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 1410-030",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 1410-040",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 3500-001",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 5310-001",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 6310-001",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 7932-001",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 2420-001",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 1400-004",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 4190-001",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 1400-004",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 3500-008",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 4190-001",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 3130-001",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 6620-001",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 3130-001",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 6620-001",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 1400-005",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 6955-001",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 5710-001",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		$event = models\Event::create(
			 array(
			 	"name" => "CS 6710-001",
			 	'professor' => $professor->id,
			 	'schedule_id' => $schedule->id,
			 	'room_id' => $room->id

		));
		
	}
}
