<?php namespace App\Lnms\Generic\Snmp;

class Arp {

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
     * poller arpAddress
     *
     * @return Array
     */
    public function poll_arpAddress() {

        // return
        $_ret = [];

        $snmp = new \App\Lnms\Snmp($this->node->ip_address, $this->node->snmp_comm_ro);

        // walk ipNetToMediaPhysAddress
        $walk_ipNetToMediaPhysAddress = $snmp->walk(OID_ipNetToMediaPhysAddress);

        if (count($walk_ipNetToMediaPhysAddress) == 0) {
            // not found arpAddress
            return $_ret;
        }

        // oid.ifIndex.ipAddress(4) = macAddress
        foreach ($walk_ipNetToMediaPhysAddress as $key1 => $macAddress) {

            $tmp1 = explode('.', str_replace(OID_ipNetToMediaPhysAddress . '.', '', $key1));

            $ifIndex = $tmp1[0];
            $ipAddress = $tmp1[1] . '.' . $tmp1[2] . '.' . $tmp1[3] . '.' . $tmp1[4];

            $port = \App\Port::where('node_id', $this->node->id)
                             ->where('ifIndex', $ifIndex)
                             ->first();

            if ($port) {
                // found port
                $_ret[] =  [ 'table'  => 'arps',
                             'action' => 'sync',
                             'key'    => [ 'node_id' => $this->node->id,
                                           'port_id' => $port->id,
                                           'ipAddress' => $ipAddress, ],
    
                             'data'   => [ 'macAddress' => $macAddress ],
                            ];
            }
        }

        return $_ret;
    }

}
