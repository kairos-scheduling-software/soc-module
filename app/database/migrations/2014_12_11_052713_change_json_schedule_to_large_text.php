<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeJsonScheduleToLargeText extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('schedules', function(Blueprint $table)
		{
			DB::statement('ALTER TABLE schedules MODIFY COLUMN json_schedule LONGTEXT');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('schedules', function(Blueprint $table)
		{
			DB::statement('ALTER TABLE schedules MODIFY COLUMN json_schedule varchar(255)');
		});
	}

}
