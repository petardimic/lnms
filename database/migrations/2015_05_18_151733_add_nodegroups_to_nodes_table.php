<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNodegroupsToNodesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('nodes', function(Blueprint $table)
		{
            $table->bigInteger('nodegroup_id')
                  ->unsigned()
                  ->after('id')
                  ->nullable();

            $table->foreign('nodegroup_id')
                  ->references('id')
                  ->on('nodegroups');
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
            $table->dropColumn('nodegroup_id');
		});
	}

}
