<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPortIndexToPortsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('ports', function(Blueprint $table)
		{
			//
            $table->integer('portIndex')
                  ->unsigned()
                  ->after('ifAlias')
                  ->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('ports', function(Blueprint $table)
		{
			//
            $table->dropColumn('portIndex');
		});
	}

}
