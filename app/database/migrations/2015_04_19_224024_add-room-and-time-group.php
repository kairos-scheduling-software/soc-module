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
			$table->increments('id');
			$table->string('name', 10)->unique();
			$table->string('description', 50);
			$table->timestamps();
		});
		
		Schema::create('room_mappings', function($table)
		{
			$table->integer('id')->unsigned();
			$table->integer('rid')->unsigned();
			$table->timestamps();
			
			$table->primary(array('id', 'rid'));
			
			$table->foreign('id')->references('id')->on('room_groups')->onDelete('cascade');
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
