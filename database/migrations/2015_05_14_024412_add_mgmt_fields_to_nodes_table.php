<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMgmtFieldsToNodesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('nodes', function(Blueprint $table)
		{
            $table->string('mgmt_method')
                  ->after('snmp_changed')
                  ->default('snmp');

            $table->string('mgmt_params')
                  ->after('mgmt_method');

            $table->string('sysLocation')->after('snmp_changed');
            $table->string('sysName')->after('snmp_changed');
            $table->string('sysContact')->after('snmp_changed');
            $table->string('sysObjectID')->after('snmp_changed');
            $table->string('sysDescr')->after('snmp_changed');


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
            $table->dropColumn('mgmt_method');
            $table->dropColumn('mgmt_params');
            $table->dropColumn('sysDescr');
            $table->dropColumn('sysObjectID');
            $table->dropColumn('sysContact');
            $table->dropColumn('sysName');
            $table->dropColumn('sysLocation');
		});
	}

}
