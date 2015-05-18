<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNodegroupsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('nodegroups', function(Blueprint $table)
		{
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('logo_file_name');
            $table->string('logo_file_type');
			$table->timestamps();

			$table->unique('name');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('nodegroups');
	}

}
