<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUsergroupIdToUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('users', function(Blueprint $table)
		{
            $table->bigInteger('usergroup_id')
                  ->unsigned()
                  ->after('id')
                  ->nullable();

            $table->foreign('usergroup_id')
                  ->references('id')
                  ->on('usergroups');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('users', function(Blueprint $table)
		{
            $table->dropColumn('usergroup_id');
		});
	}

}
