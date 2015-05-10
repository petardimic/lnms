<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePdsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pds', function(Blueprint $table)
		{
			$table->bigIncrements('id');
            $table->bigInteger('port_id')->unsigned();
            $table->bigInteger('polling_id')->unsigned();
            $table->timestamp('timestamp');
            $table->bigInteger('input')->unsigned();
            $table->bigInteger('output')->unsigned();

            $table->index(['port_id', 'polling_id', 'timestamp']);

            $table->foreign('port_id')
                  ->references('id')
                  ->on('ports')
                  ->onDelete('cascade');

            $table->foreign('polling_id')
                  ->references('id')
                  ->on('pollings')
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
		Schema::drop('pds');
	}

}
