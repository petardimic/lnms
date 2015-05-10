<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePollingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pollings', function(Blueprint $table)
		{
			$table->bigIncrements('id');
			$table->string('poll_class');               //
			$table->string('poll_method');              //
			$table->string('table_name');               // 'nodes', 'ports', ...
            $table->bigInteger('table_id')->unsigned(); // node_id, port_id
            $table->char('status', 1);         
            $table->string('interval');
			$table->timestamps();

            $table->unique(['poll_class', 'poll_method', 'table_name', 'table_id']);

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('pollings');
	}

}
