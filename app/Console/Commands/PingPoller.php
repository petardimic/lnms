<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class PingPoller extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'poller:ping';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Ping Poller Command';

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
        //$this->info('display info output');
        //$this->error('display error output');
        $nodes = \App\Node::all();

        foreach ($nodes as $node) {

            unset($exec_out1);
            exec('fping -e ' . $node->ip_address . ' 2>/dev/null', $exec_out1, $exec_ret1);
    
            if ($exec_ret1 == 0) {
                // ping ok
                $ping_success = 100;
            } else {
                // ping fail
                $ping_success = 0;
            }

            print \Carbon\Carbon::now() . ' ' . $node->ip_address  . ' ping = ' . $ping_success . PHP_EOL;
            $u_node = \App\Node::find($node->id);
            $u_node->ping_success = $ping_success;
            $u_node->save();

            $ping = new \App\Ping();
            $ping->node_id = $node->id;
            $ping->success = $ping_success;
            $ping->save();
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