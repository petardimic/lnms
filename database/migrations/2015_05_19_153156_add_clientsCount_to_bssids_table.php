<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddClientsCountToBssidsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('bssids', function(Blueprint $table)
		{
            $table->timestamp('bssidClients_updated')
                  ->after('bssidCurrentChannel');

            $table->integer('bssidClients_count')
                  ->after('bssidCurrentChannel');
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
            $table->dropColumn('bssidClients_updated');
            $table->dropColumn('bssidClients_count');
		});
	}

}
