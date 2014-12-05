<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAllSchedulesTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('schedules', function($table)
		{
			$table->increments('id');
			$table->string('name');
			$table->string('json_schedule');
			$table->timestamps();
		});

		Schema::create('schedule_user', function($table)
		{
			$table->integer('user_id')->unsigned();
			$table->integer('schedule_id')->unsigned();
			$table->unique(array('user_id', 'schedule_id'));

			$table->foreign('schedule_id')->references('id')->on('schedules')->onDelete('cascade');
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
			
		});
		
		Schema::create('professors', function($table)
		{
			$table->increments('id');
			$table->string('name')->unique();
			$table->timestamps();
		});

		Schema::create('events', function($table)
		{
			$table->increments('id');
			$table->string('name');
			$table->integer('professor')->unsigned();
			$table->foreign('professor')->references('id')->on('professors')->onDelete('cascade');


			$table->integer('schedule_id')->unsigned();
			$table->foreign('schedule_id')->references('id')->on('schedules')->onDelete('cascade');
			$table->timestamps();
		});

		Schema::create('constraints', function($table)
		{
			$table->increments('id');
			$table->string('key');
			$table->string('value');
			$table->integer('event_id')->unsigned();
			$table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
			$table->timestamps();
		});

		Schema::create('rooms', function($table)
		{
			$table->increments('id');
			$table->string('name');
			$table->integer('capacity');
			$table->string('availability');
			$table->integer('schedule_id')->unsigned();
			$table->foreign('schedule_id')->references('id')->on('schedules')->onDelete('cascade');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		
		Schema::drop('rooms');
		Schema::drop('schedule_user');
		Schema::drop('constraints');
		Schema::drop('events');
		Schema::drop('schedules');
		Schema::drop('professors');
			
	}

}
