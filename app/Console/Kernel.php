<?php namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel {

	/**
	 * The Artisan commands provided by your application.
	 *
	 * @var array
	 */
	protected $commands = [
		'App\Console\Commands\PingPoller',
		'App\Console\Commands\SnmpPoller',
		'App\Console\Commands\OctetsPoller',
		'App\Console\Commands\DiscoverPoller',
		'App\Console\Commands\PortPoller',
	];

	/**
	 * Define the application's command schedule.
	 *
	 * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
	 * @return void
	 */
	protected function schedule(Schedule $schedule)
	{
		//$schedule->command('poller:ping')
		//		 ->everyFiveMinutes();

		//$schedule->command('poller:snmp')
		//		 ->everyFiveMinutes();

		//$schedule->command('poller:octets')
		//		 ->everyFiveMinutes();

		$schedule->command('poller:port')
				 ->everyFiveMinutes();
	}

}
