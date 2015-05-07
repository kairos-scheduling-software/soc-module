<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTimeGroupTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('time_groups', function($table)
		{
			$table->increments('id');
			$table->string('name', 20);
			
			$table->timestamps();
		});
		
		Schema::create('time_mappings', function($table)
		{
			$table->integer('id')->unsigned();
			$table->integer('eid')->unsigned();
			
			$table->primary(array('id', 'eid'));
			
			$table->foreign('id')->references('id')->on('time_groups')->onDelete('cascade');
			$table->foreign('eid')->references('id')->on('etimes')->onDelete('cascade');
			
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
		Schema::drop('time_mappings');
		Schema::drop('time_groups');
	}

}
