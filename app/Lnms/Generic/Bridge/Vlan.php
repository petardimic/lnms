<?php namespace App\Lnms\Generic\Bridge;

class Vlan {

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
    public function poll_vlanName($vlanIndex='') {

        // return
        $_ret = [];

        $snmp = new \App\Lnms\Snmp($this->node->ip_address, $this->node->snmp_comm_ro);

        // walk vlanName
        $walk_vlanName = $snmp->walk(OID_vlanName);

        if (count($walk_vlanName) > 0) {

            // found vlans
            foreach ($walk_vlanName as $key1 => $value1) {
                $vlanIndex = str_replace(OID_vlanName . '.', '', $key1);
                $vlanName  = preg_replace('/"$/', '', preg_replace('/^"/', '', $value1));

                $_ret[] =  [ 'table'  => 'vlans',
                             'action' => 'sync',
                             'key'    => [ 'node_id'   => $this->node->id,
                                           'vlanIndex' => $vlanIndex ],

                             'data'   => [ 'vlanName' => $vlanName ],
                            ];
            }
        }

        return $_ret;
    }
}
