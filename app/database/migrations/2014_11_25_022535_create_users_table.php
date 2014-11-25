<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function($table)
		{
			$table->increments('id');
			$table->string('username', 50);
			$table->string('email', 50);
			$table->string('first', 50);
			$table->string('last', 50);
			$table->string('password', 60);
			$table->string('temp_password', 60);
			$table->string('activation_code', 60);

			$table->integer('active');

			$table->timestamps();

			$table->string('remember_token', 100);

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users');
	}

}
