<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMicrosecToPingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('pings', function(Blueprint $table)
		{
            $table->integer('microsec')
                  ->unsigned()
                  ->after('success');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('pings', function(Blueprint $table)
		{
            $table->dropColumn('microsec');
		});
	}

}
