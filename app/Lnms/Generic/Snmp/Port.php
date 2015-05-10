<?php namespace App\Lnms\Generic\Snmp;

class Port {

    /**
     * \App\Node
     */
    protected $node;

    /**
     * constructor
     *
     * @return void
     */
    public function __construct(\App\Node $node) {
        $this->node = $node;
    }

    /**
     * poller name
     *
     * @return Array
     */
    public function poll_ifDescr() {

        // return
        $_ret = [];

        $snmp = new \App\Lnms\Snmp($this->node->ip_address, $this->node->snmp_comm_ro);

        // walk ifDescr
        $walk_ifDescr = $snmp->walk(OID_ifDescr);

        if (count($walk_ifDescr) > 0) {
            // found ports
            foreach ($walk_ifDescr as $key1 => $value1) {
                $ifIndex = str_replace(OID_ifDescr . '.', '', $key1);
                $ifDescr = $value1;

                $_ret[] =  [ 'table'  => 'ports',
                             'action' => 'sync',
                             'key'    => [ 'node_id' => $this->node->id,
                                           'ifIndex' => $ifIndex ],

                             'data'   => [ 'ifDescr' => $ifDescr ],
                            ];
            }
        }

        return $_ret;
    }

    /**
     * common poller if
     *
     * @return Array
     */
    public function poll_if($ifOIDs, $tables = [0 => [ 'table' => 'ports', 'action' => 'update' ]]) {
        // return
        $_ret = [];

        $snmp = new \App\Lnms\Snmp($this->node->ip_address, $this->node->snmp_comm_ro);

        $oids = [];

        foreach ($this->node->ports as $port) {
            if ( is_array($ifOIDs) ) {
                foreach ($ifOIDs as $ifOID) {
                    $oids[] = constant('OID_' . $ifOID) . '.' . $port->ifIndex;
                }
            } else {
                $oids[] = constant('OID_' . $ifOIDs) . '.' . $port->ifIndex;
            }
        }

        if (count($oids) > 0) {
            $get_result = $snmp->get($oids);

            if ($get_result > 0) {

                if ( is_array($ifOIDs) ) {
                    $ifData = [];

                    foreach ($get_result as $key1 => $value1) {

                        $ifKey = preg_replace('/\.[0-9]+$/', '', $key1);
                        $ifIndex = str_replace($ifKey . '.', '', $key1);

                        foreach ($ifOIDs as $ifOID) {
                            if ( constant('OID_' . $ifOID) == $ifKey ) {
                                $ifData[$ifIndex][$ifOID] = $value1;
                            }
                        }
                    }

                    foreach ($ifData as $ifIndex => $ifValues) {
                        foreach ($tables as $table) {

                            // replace column name as defined
                            foreach ($ifValues as $ifKey => $ifValue) {
                                if ( isset($table['columns'][$ifKey]) ) {
                                    $ifDataValues[$table['columns'][$ifKey]] = $ifValue;
                                } else {
                                    $ifDataValues[$ifKey] = $ifValue;
                                }
                            }

                            $_ret[] =  [ 'table'  => $table['table'],
                                         'action' => $table['action'],
                                         'key'    => [ 'node_id' => $this->node->id,
                                                       'ifIndex' => $ifIndex ],
            
                                         'data'   => $ifDataValues,
                                        ];
                        }
                    }

                } else {
                    foreach ($get_result as $key1 => $value1) {
                        $ifIndex = str_replace(constant('OID_' . $ifOIDs) . '.', '', $key1);
                        $ifValue = $value1;

                        foreach ($tables as $table) {

                            // replace column name as defined
                            if ( isset($table['columns'][$ifOIDs]) ) {
                                $ifOIDs = $table['columns'][$ifOIDs];
                            }

                            $_ret[] =  [ 'table'  => $table['table'],
                                         'action' => $table['action'],
                                         'key'    => [ 'node_id' => $this->node->id,
                                                       'ifIndex' => $ifIndex ],
            
                                         'data'   => [ $ifOIDs   => $ifValue ],
                                        ];
                        }
                    }
                }
            }
        }

        return $_ret;
    }

    /**
     * poller ifType
     *
     * @return Array
     */
    public function poll_ifType() {

        $ifOID = 'ifType';

        return $this->poll_if($ifOID);
    }

    /**
     * poller ifStatus : ifAdminStatus, ifOperStatus
     *
     * @return Array
     */
    public function poll_ifStatus() {
        return $this->poll_if(['ifAdminStatus', 'ifOperStatus']);
    }

    /**
     * poller octets
     *
     * @return Array
     */
    public function poll_ifOctets() {

        $poll_results = $this->poll_if(['ifInOctets', 'ifOutOctets']);

        // mapping data
        for ($i=0; $i<count($poll_results); $i++) {
            $port = \App\Port::where('node_id', $this->node->id)
                            ->where('ifIndex', $poll_results[$i]['key']['ifIndex'])
                            ->first();

            $_ret[] = [ 'table'  => 'pds',
                        'action' => 'insert',
                        'key'    => [ 'port_id' => $port->id ],
                        'data'   => [ 'input'  => $poll_results[$i]['data']['ifInOctets'],
                                      'output' => $poll_results[$i]['data']['ifOutOctets'],
                                    ]
                      ];
        }

        return $_ret;
    }



}
