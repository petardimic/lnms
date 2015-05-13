<?php namespace App\Lnms\Generic\Bridge;

class Mac {

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
     * poller macAddress
     *
     * @return Array
     */
    public function poll_macAddress() {

        // return
        $_ret = [];

        $snmp = new \App\Lnms\Snmp($this->node->ip_address, $this->node->snmp_comm_ro);

        // walk OID_dot1qTpFdbPort
        $walk_dot1qTpFdbPort = $snmp->walk(OID_dot1qTpFdbPort);

        if (count($walk_dot1qTpFdbPort) == 0) {
            // not found macAddress
            return $_ret;
        }

        // dot1qTpFdbPort.vlanIndex.macDec = portIndex
        foreach ($walk_dot1qTpFdbPort as $key1 => $portIndex) {

            $tmp1 = explode('.', str_replace(OID_dot1qTpFdbPort . '.', '', $key1));

            $vlanIndex = $tmp1[0];
            $macAddress = sprintf("%02s", dechex($tmp1[1])) . ':'
                          . sprintf("%02s", dechex($tmp1[2])) . ':'
                          . sprintf("%02s", dechex($tmp1[3])) . ':'
                          . sprintf("%02s", dechex($tmp1[4])) . ':'
                          . sprintf("%02s", dechex($tmp1[5])) . ':'
                          . sprintf("%02s", dechex($tmp1[6]));
            //print "$vlanIndex $macAddress = $portIndex<br>";

            $port = \App\Port::where('node_id', $this->node->id)
                             ->where('portIndex', $portIndex)
                             ->first();

            $vlan = \App\Vlan::where('node_id', $this->node->id)
                             ->where('vlanIndex', $vlanIndex)
                             ->first();

            if ($port && $vlan) {
                // found port and vlan
                $_ret[] =  [ 'table'  => 'macs',
                             'action' => 'sync',
                             'key'    => [ 'node_id' => $this->node->id,
                                           'port_id' => $port->id,
                                           'vlan_id' => $vlan->id ],
    
                             'data'   => [ 'macAddress' => $macAddress ],
                            ];
            }

        }

        return $_ret;
    }

}
