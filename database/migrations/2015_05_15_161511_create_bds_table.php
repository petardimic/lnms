<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBdsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('bds', function(Blueprint $table)
		{
			$table->bigIncrements('id');
            $table->bigInteger('node_id')->unsigned();
            $table->bigInteger('bssid_id')->unsigned();
            $table->timestamp('timestamp');
            $table->string('clientMacAddress', 17);
            $table->string('clientIpAddress', 15);
            $table->tinyInteger('clientSignalStrength');   // dBm -100 - 0
            $table->integer('clientBytesReceived')->unsigned();
            $table->integer('clientBytesSent')->unsigned();

            $table->index(['node_id', 'bssid_id', 'clientMacAddress', 'timestamp']);

            $table->foreign('node_id')
                  ->references('id')
                  ->on('nodes')
                  ->onDelete('cascade');

            $table->foreign('bssid_id')
                  ->references('id')
                  ->on('bssids')
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
		Schema::drop('bds');
	}

}
