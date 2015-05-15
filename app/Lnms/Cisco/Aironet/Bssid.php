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

        //
        $walk_cd11IfPhyMacSpecification      = $snmp->walk(OID_cd11IfPhyMacSpecification);
        $walk_cd11IfPhyDsssMaxCompatibleRate = $snmp->walk(OID_cd11IfPhyDsssMaxCompatibleRate);
        $walk_cd11IfPhyDsssCurrentChannel    = $snmp->walk(OID_cd11IfPhyDsssCurrentChannel);
        

        // oid.ifIndex.bssidIndex = bssidName
        foreach ($walk_cd11IfAuxSsid as $key1 => $bssidName) {

            $tmp1 = explode('.', str_replace(OID_cd11IfAuxSsid . '.', '', $key1));

            $ifIndex     = $tmp1[0];
            $bssidIndex  = $tmp1[1];

            // TODO: find out which index to use, whether ifIndex or bssidIndex
            if ( isset($walk_cd11IfPhyMacSpecification[OID_cd11IfPhyMacSpecification . '.' . $ifIndex]) ) {
                $bssidSpec = $walk_cd11IfPhyMacSpecification[OID_cd11IfPhyMacSpecification . '.' . $ifIndex];
            } else {
                $bssidSpec = 0;
            }

            if ( isset($walk_cd11IfPhyDsssMaxCompatibleRate[OID_cd11IfPhyDsssMaxCompatibleRate . '.' . $ifIndex]) ) {
                // units : 500 Kb per second
                // cast to int Mbps
                $bssidMaxRate = (int) (0.5 * $walk_cd11IfPhyDsssMaxCompatibleRate[OID_cd11IfPhyDsssMaxCompatibleRate . '.' . $ifIndex]);
            } else {
                $bssidMaxRate = 0;
            }

            if ( isset($walk_cd11IfPhyDsssCurrentChannel[OID_cd11IfPhyDsssCurrentChannel . '.' . $ifIndex]) ) {
                $bssidCurrentChannel = $walk_cd11IfPhyDsssCurrentChannel[OID_cd11IfPhyDsssCurrentChannel . '.' . $ifIndex];
            } else {
                $bssidCurrentChannel = 0;
            }

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
    
                             'data'   => [ 'bssidName' => $bssidName,
                                           'bssidSpec'           => $bssidSpec,
                                           'bssidMaxRate'        => $bssidMaxRate,
                                           'bssidCurrentChannel' => $bssidCurrentChannel,
                                           ],
                            ];
            }
        }

        return $_ret;
    }

}
