<?php namespace App\Lnms\Hp\Bridge;

class Mac extends \App\Lnms\Generic\Bridge\Mac {

    /**
     * Override the parent
     *
     * @return Array
     */
    public function poll_macAddress() {
        $_ret_from_parent = parent::poll_macAddress();

        if ( count($_ret_from_parent) > 0 ) {
            return $_ret_from_parent;
        }

        // return
        $_ret = [];

        $snmp = new \App\Lnms\Snmp($this->node->ip_address, $this->node->snmp_comm_ro);

        // walk OID_dot1dTpFdbPort
        $walk_dot1dTpFdbPort = $snmp->walk(OID_dot1dTpFdbPort);

        if (count($walk_dot1dTpFdbPort) == 0) {
            // not found macAddress
            return $_ret;
        }

        // oid.macDec(6) = portIndex
        //  - portIndex = 0  // management port
        //  - no vlanIndex in this mib, so assume VLAN 1
        foreach ($walk_dot1dTpFdbPort as $key1 => $portIndex) {

            $tmp1 = explode('.', str_replace(OID_dot1dTpFdbPort . '.', '', $key1));

            $macAddress = sprintf("%02s", dechex($tmp1[0])) . ':'
                          . sprintf("%02s", dechex($tmp1[1])) . ':'
                          . sprintf("%02s", dechex($tmp1[2])) . ':'
                          . sprintf("%02s", dechex($tmp1[3])) . ':'
                          . sprintf("%02s", dechex($tmp1[4])) . ':'
                          . sprintf("%02s", dechex($tmp1[5]));

            $port = \App\Port::where('node_id', $this->node->id)
                             ->where('portIndex', $portIndex)
                             ->first();

            $vlan = \App\Vlan::where('node_id', $this->node->id)
                             ->where('vlanIndex', 1)
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
