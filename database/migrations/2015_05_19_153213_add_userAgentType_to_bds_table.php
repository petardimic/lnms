<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserAgentTypeToBdsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('bds', function(Blueprint $table)
		{
            $table->string('clientUserAgent');
            $table->string('clientUserType');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('bds', function(Blueprint $table)
		{
            $table->dropColumn('clientUserAgent');
            $table->dropColumn('clientUserType');
		});
	}

}
