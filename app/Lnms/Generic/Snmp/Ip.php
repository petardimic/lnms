<?php namespace App\Lnms\Generic\Snmp;

class Ip {

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
     * poller ipAddress
     *
     * @return Array
     */
    public function poll_ipAddress() {

        // return
        $_ret = [];

        $snmp = new \App\Lnms\Snmp($this->node->ip_address, $this->node->snmp_comm_ro);


        // walk OID_ipAdEntIfIndex
        $walk_ipAdEntIfIndex = $snmp->walk(OID_ipAdEntIfIndex);

        if (count($walk_ipAdEntIfIndex) == 0) {
            // not found ipAddress
            return $_ret;
        }

        // walk netmask
        $walk_ipAdEntNetMask = $snmp->walk(OID_ipAdEntNetMask);

        // oid.ipAddress(4) = ifIndex
        foreach ($walk_ipAdEntIfIndex as $key1 => $ifIndex) {

            $ipAddress = str_replace(OID_ipAdEntIfIndex . '.', '', $key1);
            $netmask   = explode('.', $walk_ipAdEntNetMask[OID_ipAdEntNetMask . '.' . $ipAddress]);

            $masks = substr_count(decbin($netmask[0]), 1)
                     + substr_count(decbin($netmask[1]), 1)
                     + substr_count(decbin($netmask[2]), 1)
                     + substr_count(decbin($netmask[3]), 1);

            $port = \App\Port::where('node_id', $this->node->id)
                             ->where('ifIndex', $ifIndex)
                             ->first();

            if ($port) {
                // found port and vlan
                $_ret[] =  [ 'table'  => 'ips',
                             'action' => 'sync',
                             'key'    => [ 'node_id' => $this->node->id,
                                           'port_id' => $port->id, ],
    
                             'data'   => [ 'ipAddress' => $ipAddress,
                                           'masks'     => $masks ],
                            ];
            }
        }

        return $_ret;
    }

}
