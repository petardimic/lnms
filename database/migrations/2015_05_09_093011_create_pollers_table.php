<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePollersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pollers', function(Blueprint $table)
		{
			$table->bigIncrements('id');
			$table->string('table_name');   // 'nodes', 'ports', ...
			$table->string('name');
			$table->char('status', 1);      // default status, 'Y' or 'N'
			$table->string('interval');     // Laravel schedule
			$table->timestamps();

            $table->unique(['table_name', 'name']);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('pollers');
	}

}
