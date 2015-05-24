<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class CustomPoller extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'poller:custom';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Custom Poller Command';

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
        $start_timestamp = time();

        // options
        $option_node_class    = $this->option('node_class');
        $option_poller_class  = $this->option('poller_class');
        $option_poller_method = $this->option('poller_method');

        // check class
        $poller_class  = '\App\Lnms\\' . $option_poller_class;

        if ( $option_poller_class == '' || ! class_exists($poller_class) ) {
            print 'Error: class "' . $option_poller_class . '" not exist' . "\n";
            die(1);
        }

        $nodes = \App\Node::where('poll_class', $option_node_class)
                          ->where('ping_success', 100)
                          ->where('snmp_success', 100)
                          ->orderBy('ip_address')
                          ->get();

        $counter = 0;


        foreach ($nodes as $node) {
            $poller_object = new $poller_class($node);

            if ( $option_poller_method == '' || ! method_exists($poller_object, $option_poller_method) ) {
                print 'Error: method "' . $option_poller_class . '::' . $option_poller_method  . '" not exist' . "\n";
                die(1);
            }

            $poller_result = $poller_object->$option_poller_method();

            print \Carbon\Carbon::now() . ' ' . $counter . ' ';
            print $node->ip_address . ' ' . $option_poller_method . ' ';
            print 'count=' . count($poller_result);
            print "\n";

            for ($i=0; $i<count($poller_result); $i++) {
                switch ($poller_result[$i]['action']) {
                 case 'insert':
                    // insert new
                    if ( isset($poller_result[$i]['key']) ) {
                        \DB::table($poller_result[$i]['table'])
                                    ->insert(array_merge($poller_result[$i]['key'], $poller_result[$i]['data']));
                    } else {
                        \DB::table($poller_result[$i]['table'])
                                    ->insert($poller_result[$i]['data']);
                    }
                    break;
    
                 case 'sync':
                 case 'update':
                    // query existing data by key
                    $poll_db = \DB::table($poller_result[$i]['table']);
    
                    foreach ($poller_result[$i]['key'] as $poll_key => $poll_value) {
                        $poll_db = $poll_db->where($poll_key, $poll_value);
                    }
    
                    if ($poll_db->count() > 0) {
                        // update
                        \DB::table($poller_result[$i]['table'])
                                    ->where('id', $poll_db->first()->id)
                                    ->update($poller_result[$i]['data']);
                    } else {
                        if  ($poller_result[$i]['action'] == 'sync') {
                            // just insert for 'sync'
                            if ( isset($poller_result[$i]['key']) ) {
                                \DB::table($poller_result[$i]['table'])
                                        ->insert(array_merge($poller_result[$i]['key'], $poller_result[$i]['data']));
                            } else {
                                \DB::table($poller_result[$i]['table'])
                                        ->insert($poller_result[$i]['data']);
                            }
                        }
                    }
    
                    // TODO : detect and delete removed Port from DB
                    break;
                }
            }

            $counter++;
        }


        $current_timestamp = time();
        $total_runtime = $current_timestamp - $start_timestamp;

        print \Carbon\Carbon::now() . ' ';
        print $poller_class . '(' . $option_node_class . ')::' . $option_poller_method . ' ' . count($nodes) . ' nodes, ';
        print 'runtime = ' . $total_runtime . " s.\n";
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
        // ['node_id',       null, InputOption::VALUE_REQUIRED, 'Node Id'],
		return [
            ['node_class',    null, InputOption::VALUE_REQUIRED, 'Node Class'],
			['poller_class',  null, InputOption::VALUE_REQUIRED, 'Poller Class'],
			['poller_method', null, InputOption::VALUE_REQUIRED, 'Poller Method'],
		];
	}

}
