<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropPortIdFromBssidsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('bssids', function(Blueprint $table)
		{
            $table->dropForeign('bssids_port_id_foreign');
            $table->dropColumn('port_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('bssids', function(Blueprint $table)
		{
			//
		});
	}

}
