<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoutesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('routes', function(Blueprint $table)
		{
            $table->bigIncrements('id');
            $table->bigInteger('node_id')->unsigned();
            $table->bigInteger('port_id')->unsigned();
            $table->string('routeDest', 15);
            $table->string('routeNextHop', 15);
            $table->tinyInteger('routeType')->unsigned();    // 3 : direct, 4 : indirect
            $table->tinyInteger('routeProto')->unsigned();   // 2 : local
            $table->tinyInteger('routeMasks')->unsigned();
			$table->timestamps();

			$table->unique(['node_id', 'port_id', 'routeDest', 'routeMasks']);

            $table->foreign('node_id')
                  ->references('id')
                  ->on('nodes')
                  ->onDelete('cascade');

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
		Schema::drop('routes');
	}

}
