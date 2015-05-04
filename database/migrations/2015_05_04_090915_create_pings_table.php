<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pings', function(Blueprint $table)
		{
            $table->bigIncrements('id');
            $table->bigInteger('node_id')->unsigned();
            $table->tinyInteger('success')->unsigned(); // ping success 0-100%
			$table->timestamps();

            $table->foreign('node_id')
                  ->references('id')
                  ->on('nodes')
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
		Schema::drop('pings');
	}

}
