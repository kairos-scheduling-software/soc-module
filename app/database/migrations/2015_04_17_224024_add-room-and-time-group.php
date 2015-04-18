<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRoomAndTimeGroup extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('room_groups', function($table)
		{
			$table->string('name', 10)->unique();
			$table->string('description', 50);
		});
		
		Schema::create('room_mappings', function($table)
		{
			$table->string('name', 10);
			$table->integer('rid')->unsigned();
			$table->unique(array('name', 'rid'));
			
			$table->foreign('name')->references('name')->on('room_groups')->onDelete('cascade');
			$table->foreign('rid')->references('id')->on('rooms')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('room_groups');
		Schema::drop('room_mappings');
	}

}
