<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('roles', function(Blueprint $table)
		{
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('description', 2000);
            $table->integer('user_id')->unsigned()->index();
            $table->bigInteger('usergroup_id')->unsigned()->index();
            $table->bigInteger('location_id')->unsigned()->index();
            $table->bigInteger('project_id')->unsigned()->index();

            $table->timestamps();

            $table->unique(['user_id', 'usergroup_id', 'location_id', 'project_id']);

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            $table->foreign('usergroup_id')
                  ->references('id')
                  ->on('usergroups')
                  ->onDelete('cascade');

            $table->foreign('project_id')
                  ->references('id')
                  ->on('projects')
                  ->onDelete('cascade');

            $table->foreign('location_id')
                  ->references('id')
                  ->on('locations')
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
		Schema::drop('roles');
	}

}
