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
        $nodes = \App\Node::where('ping_method', 'ping')
                          ->where('ping_params', '')
                          ->where('ping_success', 100)
                          ->get();

        foreach ($nodes as $node) {
            $snmp = new \App\Lnms\Snmp($node->ip_address, $node->snmp_comm_ro);
            $get_result = $snmp->get(OID_sysUpTime);

            if ($get_result) {
                // snmp ok
                $snmp_success  = 100;
                $get_sysUpTime   = $get_result[OID_sysUpTime];

                // get sysObjectID
                $get_result2 = $snmp->get([OID_sysObjectID, OID_sysName]);
                $get_sysObjectID = $get_result2[OID_sysObjectID];
                $get_sysName     = $get_result2[OID_sysName];
            } else {
                // snmp fail
                $snmp_success  = 0;
                $get_sysUpTime = 0;
                $get_sysObjectID = '';
                $get_sysName     = '';
            }

            print "$node->ip_address $node->snmp_comm_ro = $snmp_success $get_sysUpTime = $get_sysObjectID $get_sysName\n";

            $u_node = \App\Node::find($node->id);
            $u_node->snmp_success = $snmp_success;
            $u_node->sysObjectID  = $get_sysObjectID;
            $u_node->sysName      = $get_sysName;
            $u_node->save();
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
