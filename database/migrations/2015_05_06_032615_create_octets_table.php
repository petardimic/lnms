<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOctetsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('octets', function(Blueprint $table)
		{
			$table->bigIncrements('id');
            $table->bigInteger('port_id')->unsigned();
            $table->timestamp('timestamp');
            $table->bigInteger('input')->unsigned();
            $table->bigInteger('output')->unsigned();

            $table->index(['port_id', 'timestamp']);

            $table->foreign('port_id')
                  ->references('id')
                  ->on('ports')
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
		Schema::drop('octets');
	}

}
