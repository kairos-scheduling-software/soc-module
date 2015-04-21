<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEventEnrollcapRoomGroup extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('events', function($table) {
			$table->integer('enroll_cap')->unsigned()->after('name');
			$table->integer('room_group_id')->unsigned()->after('room_id');
			$table->foreign('room_group_id')->references('id')->on('room_groups')->onDelete('cascade');
			$table->boolean('is_rm_final')->after('room_group_id');
		});
		//
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		$table->dropColumn('enroll_cap');
		$table->dropForeign('events_room_group_id_foreign');
		$table->dropColumn('room_group_id');
		$table->dropColumn('is_rm_final');
		//
	}

}
