<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class PollersTableSeeder extends Seeder {

	/**
	 * Seed Pollers data
	 *
	 * @return void
	 */
	public function run()
	{
        // ports pollers
        \App\Poller::create(['table_name' => 'ports',
                             'name'       => 'status',
                             'status'     => 'Y',
                             'interval'   => '5'
                            ]);

        \App\Poller::create(['table_name' => 'ports',
                             'name'       => 'octets',
                             'status'     => 'Y',
                             'interval'   => '5'
                            ]);

	}

}
