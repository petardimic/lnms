<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPortVlanPivotTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('port_vlan', function(Blueprint $table)
		{
            $table->bigInteger('port_id')->unsigned()->index();
            $table->bigInteger('vlan_id')->unsigned()->index();
            $table->timestamps();

            $table->unique(['port_id', 'vlan_id']);

            $table->foreign('port_id')
                  ->references('id')
                  ->on('ports')
                  ->onDelete('cascade');

            $table->foreign('vlan_id')
                  ->references('id')
                  ->on('vlans')
                  ->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('port_vlan');
	}

}
