<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBssidsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('bssids', function(Blueprint $table)
		{
            $table->bigIncrements('id');
            $table->bigInteger('node_id')->unsigned();
            $table->bigInteger('port_id')->unsigned();
            $table->integer('bssidIndex')->unsigned();
            $table->string('bssidName');
            $table->tinyInteger('bssidSpec')->unsigned();       // 1 = 11a, 2 = 11b, 3 = 11g
            $table->integer('bssidMaxRate')->unsigned();        // Mbps 
            $table->integer('bssidCurrentChannel')->unsigned();
			$table->timestamps();

			$table->unique(['node_id', 'port_id', 'bssidIndex']);

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
		Schema::drop('bssids');
	}

}
