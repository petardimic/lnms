<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPermissionUsergroupPivotTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('permission_usergroup', function(Blueprint $table)
		{
            $table->bigInteger('permission_id')->unsigned()->index();
            $table->bigInteger('usergroup_id')->unsigned()->index();
            $table->timestamps();

            $table->unique(['permission_id', 'usergroup_id']);

            $table->foreign('permission_id')
                  ->references('id')
                  ->on('permissions')
                  ->onDelete('cascade');

            $table->foreign('usergroup_id')
                  ->references('id')
                  ->on('usergroups')
                  ->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('permission_usergroup');
	}

}
