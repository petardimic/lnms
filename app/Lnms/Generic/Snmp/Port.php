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
    public function poll_descr() {

        // return
        $_ret = [];

        $_ret['table']  = 'ports';
        $_ret['action'] = 'sync';
        $_ret['key']  = [];
        $_ret['data'] = [];

        $snmp = new \App\Lnms\Snmp($this->node->ip_address, $this->node->snmp_comm_ro);

        // walk ifDescr
        $walk_ifDescr = $snmp->walk(OID_ifDescr);


        if (count($walk_ifDescr) > 0) {
            // found ports
            foreach ($walk_ifDescr as $key1 => $value1) {
                $ifIndex = str_replace(OID_ifDescr . '.', '', $key1);
                $ifDescr = $value1;

                $_ret['key'][] =  [ 'node_id' => $this->node->id,
                                    'ifIndex' => $ifIndex ];

                $_ret['data'][] = [ 'ifDescr' => $ifDescr ];
            }
        }

        return $_ret;
    }

    /**
     * poller status
     *
     * @return Array
     */
    public function poll_status() {
    }

    /**
     * poller octets
     *
     * @return Array
     */
    public function poll_octets() {
    }

    /**
     * snmp get interfaces mibs
     *
     * @return Array
     *
     */
    public function snmpget_interfaces() {
        

        $ifOids = array( 'ifType', 'ifSpeed', 'ifPhysAddress',
                         'ifAdminStatus', 'ifOperStatus',
                         'ifName', 'ifHighSpeed', 'ifAlias' );

        foreach ($ifOids as $oid_name) {
            $get_oids = array();
            foreach ($snmp_interfaces as $ifIndex => $value1) {
                $get_oids[] = constant('OID_' . $oid_name) . '.' . $ifIndex;
            }

            $get_result = $snmp->get($get_oids);

            foreach ($snmp_interfaces as $ifIndex => $value1) {
                $snmp_interfaces[$ifIndex][$oid_name] = $get_result[constant('OID_' . $oid_name) . '.' . $ifIndex];
            }
        }

        return $snmp_interfaces;
    }

//$snmp_interfaces = self::snmpget_interfaces($node->ip_address, $node->snmp_comm_ro);
//        foreach ($snmp_interfaces as $ifIndex => $value1) {
//            $port = \App\Port::where('node_id', $id)->where('ifIndex', $ifIndex);
//            unset($value1['ifHighSpeed']);
//
//            if ($port->count() == 1) {
//                // update port
//                $port->update($value1);
//                $port = $port->firstOrFail();
//                $snmp_interfaces[$ifIndex]['poll_enabled'] = $port->poll_enabled;
//            } else {
//                // create port
//                \App\Port::create($value1);
//                $snmp_interfaces[$ifIndex]['poll_enabled'] = '';
//            }
//        }
//
//    }

}

