<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MovingJsonToTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::table('schedules', function($table)
		{
			$table->dropColumn('json_schedule');
		});

		Schema::create('etimes', function($table)
		{
			$table->increments('id');
			$table->string('starttm');
			$table->string('length');
			$table->string('days');
			$table->timestamps();
			$table->integer('event_id')->unsigned();

			$table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
		});

		Schema::table('events', function($table)
		{
			$table->string('class_type');
			$table->string('title');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('schedules', function($table)
		{
			$table->longText('json_schedule');
		});

		Schema::drop('etimes');

		Schema::table('events', function($table)
		{
			$table->dropColumn('class_type');
			$table->dropColumn('title');
		});
	}

}
