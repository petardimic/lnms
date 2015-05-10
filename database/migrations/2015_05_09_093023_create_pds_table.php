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
            $table->bigInteger('polling_id')->unsigned();
            $table->timestamp('timestamp');
            $table->bigInteger('input')->unsigned();
            $table->bigInteger('output')->unsigned();

            $table->index(['polling_id', 'timestamp']);

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
