<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropUsergroupIdFromUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('users', function(Blueprint $table)
		{
			//
            $table->dropForeign('users_usergroup_id_foreign');
            $table->dropIndex('users_usergroup_id_foreign');
            $table->dropColumn('usergroup_id');
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
			//
            $table->bigInteger('usergroup_id')
                  ->unsigned()
                  ->after('id')
                  ->nullable();
		});
	}

}
