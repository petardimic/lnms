<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNdsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('nds', function(Blueprint $table)
		{
			$table->bigIncrements('id');
            $table->bigInteger('node_id')->unsigned();
            $table->bigInteger('poller_id')->unsigned();
            $table->timestamp('timestamp');
            $table->bigInteger('ds0')->unsigned();

            $table->index(['node_id', 'poller_id', 'timestamp']);

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
		Schema::drop('nds');
	}

}
