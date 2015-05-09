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
            $table->bigInteger('poller_id')->unsigned();
            $table->bigInteger('table_id')->unsigned();     // node_id, port_id
            $table->char('status', 1);         
            $table->string('interval');
			$table->timestamps();

            $table->unique(['poller_id', 'table_id']);

            $table->foreign('poller_id')
                  ->references('id')
                  ->on('pollers')
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
		Schema::drop('pollings');
	}

}
