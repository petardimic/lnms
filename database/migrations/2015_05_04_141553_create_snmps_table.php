<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSnmpsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('snmps', function(Blueprint $table)
		{
            $table->bigIncrements('id');
            $table->bigInteger('node_id')->unsigned();
            $table->timestamp('timestamp');
            $table->integer('sysUpTime')->unsigned();

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
		Schema::drop('snmps');
	}

}
