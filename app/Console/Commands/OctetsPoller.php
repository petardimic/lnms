<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class OctetsPoller extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'poller:octets';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Input/Output Octets Poller Command';

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
        $ports = \App\Port::all();

        foreach ($ports as $port) {

            //print "$port->node->ip_address $port->ifIndex\n";
            $snmp = new \App\Lnms\Snmp($port->node->ip_address, $port->node->snmp_comm_ro, '2c');
            $get_result = $snmp->get([OID_ifHCInOctets  . '.' . $port->ifIndex,
                                      OID_ifHCOutOctets . '.' . $port->ifIndex
                                      ]);

            if ($get_result) {
                // get ok
                $octet = \App\Octet::Create([
                    'port_id'   => $port->id,
                    'timestamp' => \Carbon\Carbon::now(),
                    'input'     => $get_result[OID_ifHCInOctets  . '.' . $port->ifIndex],
                    'output'    => $get_result[OID_ifHCOutOctets . '.' . $port->ifIndex],
                ]);
            } else {
                // get errors
            }
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
