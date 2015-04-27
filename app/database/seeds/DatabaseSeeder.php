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
        DB::table('tickets')->delete();
		DB::table('users')->delete();
		$user = User::create(
			array(
				'username' => 'admin',
				'email' => 'foo@bar.com',
				'is_admin' => true,
				'first' => 'admin',
				'last' => 'admin',
				'password' => Hash::make('admin'),
				'temp_password' => '',
				'activation_code' => '',
				'active' => 1,
				'remember_token' => '' 
		));

		DB::table('schedules')->delete();
		DB::table('schedule_user')->delete();
        DB::table('professors')->delete();
        DB::table('rooms')->delete();
        DB::table('etimes')->delete();
        DB::table('events')->delete();
        DB::table('constraints')->delete();

        Etime::create(array(
            'id' => 1, 
            'standard_block' => 0
        ));

        //create the standard timeblocks leaving only the non standard
        $mwStimes = array('0805', '1150', '1325', '1500');
        $tthStimes = array('0730', '0910', '1045', '1225', '1400', '1540');
        $mwfSTimes = array('0730', '0835', '0940', '1045', '1150', '1255', '1400', '1505', '1610');
		$labTimes = array('0730',  '0940', '1045', '1255', '1400', '1505', '1610');

        foreach ($mwStimes as $starttm) 
        {
            Etime::create(array(
                'starttm' => $starttm, 
                'length' =>80, 
                'days' => '1|3', 
                'standard_block' => 1
            ));

            Etime::create(array(
                'starttm' => $starttm, 
                'length' =>80, 
                'days' => '1', 
                'standard_block' => 1
            ));

            Etime::create(array(
                'starttm' => $starttm, 
                'length' =>80, 
                'days' => '3', 
                'standard_block' => 1
            ));
        }

        foreach ($tthStimes as $starttm) {
            Etime::create(array(
                'starttm' => $starttm, 
                'length' =>80, 
                'days' => '2|4', 
                'standard_block' => 1
            ));

            Etime::create(array(
                'starttm' => $starttm, 
                'length' =>80, 
                'days' => '2', 
                'standard_block' => 1
            ));

            Etime::create(array(
                'starttm' => $starttm, 
                'length' =>80, 
                'days' => '4', 
                'standard_block' => 1
            ));
        }

        foreach ($mwfSTimes as $starttm) {
            Etime::create(array(
                'starttm' => $starttm, 
                'length' =>50, 
                'days' => '1|3|5', 
                'standard_block' => 1
            ));

            Etime::create(array(
                'starttm' => $starttm, 
                'length' =>50, 
                'days' => '1|3', 
                'standard_block' => 1
            ));

            Etime::create(array(
                'starttm' => $starttm, 
                'length' =>50, 
                'days' => '1', 
                'standard_block' => 1
            ));

            Etime::create(array(
                'starttm' => $starttm, 
                'length' =>50, 
                'days' => '3', 
                'standard_block' => 1
            ));

            Etime::create(array(
                'starttm' => $starttm, 
                'length' =>50, 
                'days' => '5', 
                'standard_block' => 1
            ));
        }

        foreach ($labTimes as $starttm) 
        {
            Etime::create(array(
                'starttm' => $starttm, 
                'length' =>50, 
                'days' => '2', 
                'standard_block' => 1
            ));

            Etime::create(array(
                'starttm' => $starttm, 
                'length' =>50, 
                'days' => '4', 
                'standard_block' => 1
            ));
        }

        $room = Room::create(
            array(
                'id'   => 1,
                'name' => 'Unassigned',
                'capacity' => 0,
                'availability' => '',
        ));

        $professor = Professor::firstOrNew(
            array(
                'id'   => 1,
                'uid'  => 'U0000000',
                'name' => 'Unassigned'
        ));

        ini_set('auto_detect_line_endings', true);

        $dir = File::allFiles('app/database/SeederDataFiles/');
        foreach ($dir as $fileinfo) 
        {
            
            $temp = $fileinfo->getFilename();

            if(substr($temp, strlen($temp)-4) !== ".csv")
            {
                continue;
            }

            $temp = substr($temp, 0, strlen($temp) - 4);
            $name = explode( '_', $temp );

            $schedule = Schedule::create(
                array(
                    'name' => $name[0] . " " . $name[1],
                    'description' => '',
                    'semester' => $name[0],
                    'year'     => $name[1],
                    'final'    => true,
                    'last_edited_by' => $user->id
                ));

                $user->schedules()->attach($schedule);
                DataConvertUtils::importFullSchedule($schedule, 'app/database/SeederDataFiles/' . $fileinfo->getFilename());
        }

	}
}
