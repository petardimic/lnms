<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNodesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('nodes', function(Blueprint $table)
		{
			$table->bigIncrements('id');
            $table->string('name');
            $table->string('ip_address');

            $table->string('ping_method')->default('ping');
            $table->string('ping_params')->default('');
            $table->tinyInteger('ping_success')->unsigned();
            $table->timestamp('ping_changed');

            $table->tinyInteger('snmp_version')->unsigned()->default(0);    // 0=SNMP disabled
            $table->string('snmp_comm_ro')->default('');
            $table->string('snmp_comm_rw')->default('');
            $table->timestamp('snmp_changed');
			$table->timestamps();

            // index
            $table->index('name');
            $table->index('ip_address');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('nodes');
	}

}
