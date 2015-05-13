<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIpsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ips', function(Blueprint $table)
		{
            $table->bigIncrements('id');
            $table->bigInteger('node_id')->unsigned();
            $table->bigInteger('port_id')->unsigned();
            $table->string('ipAddress', 15);
            $table->tinyInteger('masks')->unsigned();   // Subnet mask bits
			$table->timestamps();

			$table->unique(['node_id', 'port_id', 'ipAddress']);

            $table->foreign('node_id')
                  ->references('id')
                  ->on('nodes')
                  ->onDelete('cascade');

            $table->foreign('port_id')
                  ->references('id')
                  ->on('ports')
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
		Schema::drop('ips');
	}

}
