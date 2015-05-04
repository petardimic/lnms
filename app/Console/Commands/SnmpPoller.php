<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class SnmpPoller extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'poller:snmp';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'SNMP Poller Command';

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
        $nodes = \App\Node::all();

        foreach ($nodes as $node) {

            $snmp = new \App\Lnms\Snmp($node->ip_address, $node->snmp_comm_ro);
            $get_result = $snmp->get(OID_sysUpTime);

            if ($get_result) {
                // snmp ok
                $get_sysUpTime = $get_result[OID_sysUpTime];
                $snmp_success = 100;
            } else {
                // snmp fail
                $snmp_success = 0;
                $get_sysUpTime = 0;
            }

            $u_node = \App\Node::find($node->id);
            $u_node->snmp_success = $snmp_success;
            $u_node->save();

            $snmp = new \App\Snmp();
            $snmp->node_id   = $node->id;
            $snmp->success   = $snmp_success;
            $snmp->timestamp = \Carbon\Carbon::now();

            $snmp->save();
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
