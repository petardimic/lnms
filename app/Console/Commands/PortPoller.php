<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class PortPoller extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'poller:port';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Port Poller Command';

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
        $pollings = \App\Polling::where('table_name', 'ports')
                                ->where('status', 'Y')
                                ->get();

        foreach ($pollings as $polling) {

            $port = \App\Port::findOrFail($polling->table_id);

            if ($port->poll_enabled == 'Y') {

                $poll_class = '\App\Lnms\\' . $port->node->poll_class . '\\' . $polling->poll_class;
    
                $poll_object = new $poll_class($port->node);
    
                $poll_method = $polling->poll_method;
    
                $poller_result = $poll_object->$poll_method($port->ifIndex);
    
                for ($i=0; $i<count($poller_result); $i++) {
    
                    if ( $poller_result[$i]['table'] == 'pds' ) {
                        unset($poller_result[$i]['key']);
    
                        $poller_result[$i]['key'] = ['polling_id' => $polling->id,
                                                     'timestamp'  => \Carbon\Carbon::now()
                                                    ];
    
                    }
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
