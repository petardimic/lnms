<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLocationsToNodesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('nodes', function(Blueprint $table)
		{
			//
            $table->bigInteger('location_id')
                  ->unsigned()
                  ->after('id')
                  ->nullable();

            $table->foreign('location_id')
                  ->references('id')
                  ->on('locations');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('nodes', function(Blueprint $table)
		{
			//
            $table->dropColumn('location_id');
		});
	}

}
