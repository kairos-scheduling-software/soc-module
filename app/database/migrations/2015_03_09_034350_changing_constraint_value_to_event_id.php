<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangingConstraintValueToEventId extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('constraints', function(Blueprint $table)
		{
			DB::statement('ALTER TABLE constraints MODIFY COLUMN value INT unsigned');
			$table->foreign('value')->references('id')->on('events')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('constraints', function(Blueprint $table)
		{
			$table->dropForeign('constraints_value_foreign');
			DB::statement('ALTER TABLE schedules MODIFY COLUMN json_schedule LONGTEXT');
		});
	}

}
