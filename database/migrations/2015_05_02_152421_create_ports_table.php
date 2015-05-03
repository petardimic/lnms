<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePortsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ports', function(Blueprint $table)
		{
            $table->bigIncrements('id');
            $table->bigInteger('node_id')->unsigned();
            $table->integer('ifIndex')->unsigned();
            $table->string('ifDescr');
            $table->integer('ifType')->unsigned();
            $table->bigInteger('ifSpeed')->unsigned();
            $table->string('ifPhysAddress');
            $table->tinyInteger('ifAdminStatus')->unsigned();
            $table->tinyInteger('ifOperStatus')->unsigned();
            $table->string('ifName');
            $table->string('ifAlias');
            $table->char('poll_enabled', 1);

            $table->timestamp('status_changed');
			$table->timestamps();

			$table->unique(['node_id', 'ifIndex']);

            $table->foreign('node_id')
                  ->references('id')
                  ->on('nodes')
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
		Schema::drop('ports');
	}

}
