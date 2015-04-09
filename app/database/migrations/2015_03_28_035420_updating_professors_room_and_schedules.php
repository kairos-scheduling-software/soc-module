<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatingProfessorsRoomAndSchedules extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('rooms', function($table)
		{
			$table->dropForeign('rooms_schedule_id_foreign');
    		$table->dropColumn('schedule_id');
		});

		Schema::table('schedules', function($table)
		{
			$table->char('semester', 10)->after('name');
			$table->char('year', 4)->after('semester');
			$table->boolean('final')->after('year');
		});

		Schema::create('room_schedule', function($table)
		{
			$table->integer('room_id')->unsigned();
			$table->integer('schedule_id')->unsigned();
			$table->unique(array('room_id', 'schedule_id'));

			$table->foreign('schedule_id')->references('id')->on('schedules')->onDelete('cascade');
			$table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');
			
		});

		Schema::create('professor_schedule', function($table)
		{
			$table->integer('professor_id')->unsigned();
			$table->integer('schedule_id')->unsigned();
			$table->unique(array('professor_id', 'schedule_id'));

			$table->foreign('schedule_id')->references('id')->on('schedules')->onDelete('cascade');
			$table->foreign('professor_id')->references('id')->on('professors')->onDelete('cascade');
			
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('rooms', function($table)
		{
    		$table->integer('schedule_id')->unsigned();
    		$table->foreign('schedule_id')->references('id')->on('schedules')->onDelete('cascade');
		});

		Schema::drop('room_schedule');
		Schema::drop('professor_schedule');
	}

}
