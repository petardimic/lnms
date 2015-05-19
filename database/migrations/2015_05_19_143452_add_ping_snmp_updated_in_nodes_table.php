<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPingSnmpUpdatedInNodesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('nodes', function(Blueprint $table)
		{
            $table->integer('ping_microsec')
                  ->unsigned()
                  ->after('ping_success');

            $table->timestamp('ping_updated')
                  ->after('ping_changed');

            $table->integer('sysUpTime')
                  ->unsigned()
                  ->after('sysObjectID');

            $table->timestamp('snmp_updated')
                  ->after('snmp_changed');


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
            $table->dropColumn('ping_microsec');
            $table->dropColumn('ping_updated');
            $table->dropColumn('sysUpTime');
            $table->dropColumn('snmp_updated');
		});
	}

}
