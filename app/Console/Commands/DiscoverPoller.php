<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class DiscoverPoller extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'poller:discover';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Discover Poller Command';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		//
        $nodes = \App\Node::where('ping_success', '100')
                          ->where('snmp_success', '100')
                          ->get();

        foreach ($nodes as $node) {
            $discover_result = \App\Http\Controllers\NodesController::execDiscover($node->id);
            print "$node->ip_address = " . $discover_result . PHP_EOL;
        }
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [];
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return [];
	}

}
