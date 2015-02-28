<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddingResolveColumnToTickets extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('tickets', function($table) 
		{
			$table->boolean('resolve')->after('id');
			$table->integer('user')->unsigned()->nullable();
			$table->foreign('user')->references('id')->on('users')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('tickets', function($table) 
		{
			$table->dropColumn('resolve');
			$table->dropColumn('user');
		});
	}

}
