<?php namespace App\Lnms\Cisco\Aironet;

class Bssid {

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
     * poller ssidName
     *
     * @return Array
     */
    public function poll_ssidName() {

        // return
        $_ret = [];

        $snmp = new \App\Lnms\Snmp($this->node->ip_address, $this->node->snmp_comm_ro);

        // walk cd11IfAuxSsid
        $walk_cd11IfAuxSsid = $snmp->walk(OID_cd11IfAuxSsid);

        if (count($walk_cd11IfAuxSsid) == 0) {
            // not found cd11IfAuxSsid
            return $_ret;
        }

        // oid.ifIndex.bssidIndex = bssidName
        foreach ($walk_cd11IfAuxSsid as $key1 => $ssidName) {

            $tmp1 = explode('.', str_replace(OID_cd11IfAuxSsid . '.', '', $key1));

            $ifIndex = $tmp1[0];
            $bssidIndex = $tmp1[1];

            $port = \App\Port::where('node_id', $this->node->id)
                             ->where('ifIndex', $ifIndex)
                             ->first();

            if ($port) {
                // found port
                $_ret[] =  [ 'table'  => 'bssids',
                             'action' => 'sync',
                             'key'    => [ 'node_id' => $this->node->id,
                                           'port_id' => $port->id,
                                           'bssidIndex' => $bssidIndex,
                                           ],
    
                             'data'   => [ 'ssidName' => $ssidName ],
                            ];
            }
        }

        return $_ret;
    }

}
