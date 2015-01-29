<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EtimeToStandardblocks extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('etimes', function(Blueprint $table)
		{
			$table->dropForeign('etimes_event_id_foreign');
			$table->dropColumn('event_id');
			$table->integer('standard_block');
		});

		Schema::table('events', function(Blueprint $table)
		{
			$table->integer('etime_id')->unsigned();
			$table->foreign('etime_id')->references('id')->on('etimes')->onDelete('cascade');

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('events', function(Blueprint $table)
		{
			$table->dropForeign('events_etime_id_foreign');
			$table->dropColumn('etime_id');
		});

		Schema::table('etimes', function(Blueprint $table)
		{
			$table->integer('event_id')->unsigned();
			$table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
			
			$table->dropColumn('standard_block');
		});
	}

}
