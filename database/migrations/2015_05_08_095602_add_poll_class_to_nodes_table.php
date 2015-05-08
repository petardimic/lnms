<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPollClassToNodesTable extends Migration {

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
            $table->string('poll_class')->nullable()->after('poll_enabled');
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
            $table->dropColumn('poll_class');
		});
	}

}
