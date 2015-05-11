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
     * poller vlanName
     *
     * @return Array
     */
    public function poll_vlanName($vlanIndex='') {

        // return
        $_ret = [];

        $snmp = new \App\Lnms\Snmp($this->node->ip_address, $this->node->snmp_comm_ro);

        // walk vlanName
        //$walk_vlanName = $snmp->walk(OID_dot1qVlanStaticName);

        // walk vlanName
        $walk_vlanStatus = $snmp->walk(OID_dot1qVlanStatus);

        if (count($walk_vlanStatus) > 0) {

            // found vlans
            foreach ($walk_vlanStatus as $key1 => $value1) {
                $vlanIndex = str_replace(OID_dot1qVlanStatus . '.', '', $key1);

                if ( preg_match('/\./', $vlanIndex) ) {
                    // if found 0.1, then convert to 1
                    $vlanIndex = preg_replace('/^[^\.]+\./', '', $vlanIndex);
                }

                $vlanName = $snmp->get([OID_dot1qVlanStaticName . '.' . $vlanIndex]);

                // some vlan does not have name defined
                if ($vlanName[OID_dot1qVlanStaticName . '.' . $vlanIndex] === false) {
                    $vlanName = $vlanIndex;
                } else {
                    $vlanName = $vlanName[OID_dot1qVlanStaticName . '.' . $vlanIndex];
                }

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

    /**
     * poller vlanMember
     *
     * @return Array
     */
    public function poll_vlanMember($vlanIndex='') {

        // return
        $_ret = [];

        $snmp = new \App\Lnms\Snmp($this->node->ip_address, $this->node->snmp_comm_ro);

        // disable line breaks in output
        $snmp->setOptions('--hexOutputLength=0');

        if ($this->node->vlans->count() == 0) {
            // no vlan
            return $_ret;
        }
        
        foreach ($this->node->vlans as $vlan) {
            // why has .0.
            $oids[] = OID_dot1qVlanCurrentEgressPorts . '.0.' . $vlan->vlanIndex;
        }

        if (count($oids) == 0) {
            // no oid to poll
            return $_ret;
        }

        $get_result = $snmp->get($oids);

        if ( count($get_result) == 0 ) {
            // cannot get anything
            return $_ret;
        }

        $members = [];

        foreach ($get_result as $key1 => $value1) {
            $vlanIndex = str_replace(OID_dot1qVlanCurrentEgressPorts . '.0.', '', $key1);
            $vlanValue = $value1;

            $tmp_hex = explode(' ', $value1);


            for ($i=0; $i<count($tmp_hex); $i++) {
                if ($tmp_hex[$i] <> '00') {
                    $tmp_bin = sprintf("%08s", decbin(hexdec($tmp_hex[$i])));
                    for ($j=0; $j<strlen($tmp_bin); $j++) {
                        if ($tmp_bin[$j] == 1) {
                            $portIndex = ($i*8) + $j + 1;

                            $members[$portIndex][] = $vlanIndex;
                        }
                    }
                }
            }
        }

        //
        if ( count($members) == 0 ) {
            // cannot get any members
            return $_ret;
        }

        foreach ($members as $memberPortIndex => $memberVlans) {

            // TODO: check query error
            $mVlans = [];

            foreach ($memberVlans as $memberVlan) {

                $mVlan = \App\Vlan::where('node_id', $this->node->id)
                                  ->where('vlanIndex', $memberVlan)
                                  ->first();
                $mVlans[] = $mVlan->id;

            }

            //
            $mPort = \App\Port::where('node_id', $this->node->id)
                              ->where('portIndex', $memberPortIndex)
                              ->first();
            $mPort->vlans()->sync($mVlans);

        }

        return $_ret;
    }
}
