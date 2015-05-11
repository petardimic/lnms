<?php namespace App\Lnms\Generic\Bridge;

class Port extends \App\Lnms\Generic\Snmp\Port {

    /**
     * poller name
     *
     * @return Array
     */
    public function poll_portIndex($ifIndex='') {

        // return
        $_ret = [];

        $snmp = new \App\Lnms\Snmp($this->node->ip_address, $this->node->snmp_comm_ro);

        // walk portIndex
        $walk_portIndex = $snmp->walk(OID_dot1dBasePortIfIndex);

        if (count($walk_portIndex) > 0) {
            // found ports
            foreach ($walk_portIndex as $key1 => $value1) {
                $portIndex = str_replace(OID_dot1dBasePortIfIndex . '.', '', $key1);
                $ifIndex   = $value1;

                $_ret[] =  [ 'table'  => 'ports',
                             'action' => 'update',
                             'key'    => [ 'node_id' => $this->node->id,
                                           'ifIndex' => $ifIndex ],

                             'data'   => [ 'portIndex' => $portIndex ],
                            ];
            }
        }

        return $_ret;
    }
}
