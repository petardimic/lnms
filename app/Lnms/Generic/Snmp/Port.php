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
    public function poll_ifDescr($ifIndex='') {

        // return
        $_ret = [];

        if ($ifIndex <> '') {
            $poll_results = $this->poll_if('ifDescr', $ifIndex);
            return $poll_results;
        }

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

                             'data'   => [ 'ifDescr' => $ifDescr, ],
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
    //public function poll_if($ifOIDs, $tables = [0 => [ 'table' => 'ports', 'action' => 'update' ]]) {

    public function poll_if($ifOIDs, $ifIndex='') {

        // return
        $_ret = [];

        $snmp = new \App\Lnms\Snmp($this->node->ip_address, $this->node->snmp_comm_ro);

        $oids = [];

        if ($ifIndex == '') {

            foreach ($this->node->ports as $port) {


                if ( is_array($ifOIDs) ) {
                    foreach ($ifOIDs as $ifOID) {
                        $oids[] = constant('OID_' . $ifOID) . '.' . $port->ifIndex;
                    }
                } else {
                    $oids[] = constant('OID_' . $ifOIDs) . '.' . $port->ifIndex;
                }
            }
        } else {

            if ( is_array($ifOIDs) ) {
                foreach ($ifOIDs as $ifOID) {
                    $oids[] = constant('OID_' . $ifOID) . '.' . $ifIndex;
                }
            } else {
                $oids[] = constant('OID_' . $ifOIDs) . '.' . $ifIndex;
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
                        $_ret[] =  [ 'table'  => 'ports',
                                     'action' => 'update',
                                     'key'    => [ 'node_id' => $this->node->id,
                                                  'ifIndex' => $ifIndex ],
            
                                     'data'   => $ifValues,
                                    ];
                    }

                } else {
                    foreach ($get_result as $key1 => $value1) {
                        $ifIndex = str_replace(constant('OID_' . $ifOIDs) . '.', '', $key1);
                        $ifValue = $value1;

                        $_ret[] =  [ 'table'  => 'ports',
                                     'action' => 'update',
                                     'key'    => [ 'node_id' => $this->node->id,
                                                   'ifIndex' => $ifIndex ],
            
                                     'data'   => [ $ifOIDs   => $ifValue ],
                                    ];
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
    public function poll_ifType($ifIndex='') {

        $poll_results = $this->poll_if('ifType', $ifIndex);

        // mapping data
        for ($i=0; $i<count($poll_results); $i++) {

            $_ret[] = [ 'table'  => 'ports',
                        'action' => 'update',
                        'key'    => $poll_results[$i]['key'],
                        'data'   => $poll_results[$i]['data'], 
                      ];

            // update ifType
            switch ($poll_results[$i]['data']['ifType']) {
             case '24':  // softwareLoopback
             case '53':  // propVirtual
             case '131': // tunnel
                $u_poll_enabled = 'N';
                break;

             default:
                $u_poll_enabled = 'Y';
                break;
            }

            $_ret[] = [ 'table'  => 'ports',
                        'action' => 'update',
                        'key'    => $poll_results[$i]['key'],
                        'data'   => ['poll_enabled' => $u_poll_enabled],
                      ];
        }

        return $_ret;

    }

    /**
     * poller ifStatus : ifAdminStatus, ifOperStatus
     *
     * @return Array
     */
    public function poll_ifStatus($ifIndex='') {
        return $this->poll_if(['ifAdminStatus', 'ifOperStatus'], $ifIndex);
    }

    /**
     * poller octets
     *
     * @return Array
     */
    public function poll_ifOctets($ifIndex='') {

        if ($ifIndex == '') {
            // poll only one port, specified by ifIndex
            return [];
        }

        $port = \App\Port::where('node_id', $this->node->id)
                         ->where('ifIndex', $ifIndex)
                         ->first();

        if ($port->ifOperStatus <> 1) {
            return [];
        }

        // poll only Port Up
        $poll_results = $this->poll_if(['ifInOctets', 'ifOutOctets'], $ifIndex);

        // mapping data
        for ($i=0; $i<count($poll_results); $i++) {

            $_ret[] = [ 'table'  => 'pds',
                        'action' => 'insert',
                        'key'    => $poll_results[$i]['key'],
                        'data'   => [ 'input'  => $poll_results[$i]['data']['ifInOctets'],
                                      'output' => $poll_results[$i]['data']['ifOutOctets'],
                                    ]
                      ];
        }


        return $_ret;
    }

    /**
     * poller ifSpeed
     *
     * @return Array
     */
    public function poll_ifSpeed($ifIndex='') {
        return $this->poll_if('ifSpeed', $ifIndex);
    }

    /**
     * poller ifName
     *
     * @return Array
     */
    public function poll_ifName($ifIndex='') {
        return $this->poll_if('ifName', $ifIndex);
    }

    /**
     * poller ifAlias
     *
     * @return Array
     */
    public function poll_ifAlias($ifIndex='') {
        return $this->poll_if('ifAlias', $ifIndex);
    }

    /**
     * poller ifPhysAddress
     *
     * @return Array
     */
    public function poll_ifPhysAddress($ifIndex='') {
        return $this->poll_if('ifPhysAddress', $ifIndex);
    }

}
