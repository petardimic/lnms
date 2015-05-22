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

        // delay to prevent run the same time with another job
        sleep((($this->argument('endIfIndex') % 2) * 30) +5);

        print \Carbon\Carbon::now() . ' Start ';
        print $this->name . '(' . $this->argument('endIfIndex') . ') ';
        print "\n";
        $start_timestamp = time();

		// now test poll only ethernet(6) Up
        $ports = \App\Port::where('ifType', '6')
                          ->where('ifOperStatus', '1')
                          ->where('ifIndex', 'LIKE', '%' . $this->argument('endIfIndex'))
                          ->where('poll_enabled', 'Y')
                          ->orderBy('ifIndex')
                          ->get();

        $counter = 0;

        foreach ($ports as $port) {

            if ( $port->node->ping_success == 100
                 && $port->node->snmp_success == 100
                 && $port->node->snmp_version > 0
                 ) {

                $snmp = new \App\Lnms\Snmp($port->node->ip_address, $port->node->snmp_comm_ro, $port->node->snmp_version);

                if ($port->node->snmp_version == 2 ) {
                    $oid_input  = OID_ifHCInOctets;
                    $oid_output = OID_ifHCOutOctets;
                } else {
                    $oid_input  = OID_ifInOctets;
                    $oid_output = OID_ifOutOctets;
                }

                $get_result = $snmp->get([$oid_input  . '.' . $port->ifIndex,
                                          $oid_output . '.' . $port->ifIndex
                                          ]);


                $current_timestamp = time();
                $diff_timestamp = $current_timestamp - $start_timestamp;

                print \Carbon\Carbon::now() . ' ';
                print $this->name . '(' . $this->argument('endIfIndex') . ') ';
                print $counter . ' (' . $diff_timestamp . ' s.) - ';
                print $port->node->ip_address . ' ' . $port->ifIndex . ' = ';

                if ($get_result) {
                    // get ok
                    print $get_result[$oid_input   . '.' . $port->ifIndex] . ' / ';
                    print $get_result[$oid_output  . '.' . $port->ifIndex] . ' ';

                    if ( (int) $get_result[$oid_input   . '.' . $port->ifIndex] > 0 
                         || (int) $get_result[$oid_output   . '.' . $port->ifIndex] > 0 ) {

                        $octet = \App\Octet::Create([
                            'port_id'   => $port->id,
                            'timestamp' => \Carbon\Carbon::now(),
                            'input'     => $get_result[$oid_input  . '.' . $port->ifIndex],
                            'output'    => $get_result[$oid_output . '.' . $port->ifIndex],
                        ]);

                    } else {
                        print ' *** get error *** ';

                        // disable polling
                        $port->poll_enabled = 'N';
                        $port->save();
                    }

                } else {
                    // get errors
                    print ' *** cannot get the value *** ';

                    // disable polling
                    $port->poll_enabled = 'N';
                    $port->save();
                }

                print "\n";
            }

            $counter++;
        }

        $current_timestamp = time();
        $total_runtime = $current_timestamp - $start_timestamp;
        
        print \Carbon\Carbon::now() . ' Stop ';
        print $this->name . '(' . $this->argument('endIfIndex') . ') ';
        print $counter . ' records, ';
        print 'runtime = ' . $total_runtime . " s.\n";
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [
            ['endIfIndex', InputArgument::REQUIRED, 'end of ifIndex']
        ];
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
