<?php namespace App\Lnms\Colubris\Ap;


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
     * poller bssidName
     *
     * @return Array
     */
    public function poll_bssidName() {

        // return
        $_ret = [];

        $snmp = new \App\Lnms\Snmp($this->node->ip_address, $this->node->snmp_comm_ro);

        $walk_coDot11BSSID = $snmp->walk(OID_Colubris_coDot11BSSID);

        if (count($walk_coDot11BSSID) == 0 || $walk_coDot11BSSID === false) {
            // not found coDot11BSSID
            return $_ret;
        }

        // oid.ifIndex.bssidIndex = bssidMacHex
        foreach ($walk_coDot11BSSID as $key1 => $bssidMacHex) {


            $bssidSuffix = str_replace(OID_Colubris_coDot11BSSID . '.', '', $key1);
            $bssidMacAddress = str_replace(' ', ':', $bssidMacHex);

            $tmp1 = explode('.', $bssidSuffix);

            $ifIndex     = $tmp1[0];
            $bssidIndex  = $tmp1[1];

            $port = \App\Port::where('node_id', $this->node->id)
                             ->where('ifIndex', $ifIndex)
                             ->first();
            if ($port) {
                // found port

                $bssidDetails = $snmp->get([OID_Colubris_coVscCfgSSID . '.' . $bssidIndex]);
                // bssidName
                if ($bssidDetails[OID_Colubris_coVscCfgSSID . '.' . $bssidIndex] === false) {
                    $bssidName = '';
                } else {
                    $bssidName = $bssidDetails[OID_Colubris_coVscCfgSSID . '.' . $bssidIndex];
                }

                $_ret[] =  [ 'table'  => 'bssids',
                             'action' => 'sync',
                             'key'    => [ 'node_id' => $this->node->id,
                                           'port_id' => $port->id,
                                           'bssidIndex' => $bssidIndex,
                                           ],
    
                             'data'   => [ 'bssidMacAddress'     => $bssidMacAddress,
                                           'bssidName'           => $bssidName,
                                           'bssidSpec'           => 0,
                                           'bssidMaxRate'        => 0,
                                           'bssidCurrentChannel' => 0,
                                           ],
                            ];
            }
        }

        return $_ret;
    }

    /**
     * poller clientMacAddress
     *
     * @return Array
     */
    public function poll_clientMacAddress() {

        // return
        $_ret = [];

        $snmp = new \App\Lnms\Snmp($this->node->ip_address, $this->node->snmp_comm_ro);

        // walk coDot11StationMACAddress
        $walk_coDot11StationMACAddress = $snmp->walk(OID_Colubris_coDot11StationMACAddress);

        if (count($walk_coDot11StationMACAddress) == 0 || $walk_coDot11StationMACAddress === false) {
            // not found coDot11StationMACAddress
            return $_ret;
        }

        // oid.ifIndex.clientIndex
        foreach ($walk_coDot11StationMACAddress as $key1 => $clientMacHex) {

            $clientMacAddress = str_replace(' ', ':', $clientMacHex);

            $clientSuffix = str_replace(OID_Colubris_coDot11StationMACAddress . '.', '', $key1);
            $tmp1 = explode('.', $clientSuffix);

            $ifIndex = $tmp1[0];
            $clientIndex = $tmp1[1];

            // find port_id
            $port = \App\Port::where('node_id', $this->node->id)
                             ->where('ifIndex', $ifIndex)
                             ->first();

            if ($port) {
                // find bssid
                $bssid = \App\Bssid::where('node_id', $this->node->id)
                                   ->where('port_id', $port->id)
                                   ->first();
    
                //
                $clientDetails = $snmp->get([ OID_Colubris_coDot11StationIPAddress . '.' . $clientSuffix,
                                              OID_Colubris_coDot11SignalLevel      . '.' . $clientSuffix,
                                             ]);
    
                if ( isset($clientDetails[OID_Colubris_coDot11StationIPAddress . '.' . $clientSuffix]) ) {
                    $clientIpAddress = $clientDetails[OID_Colubris_coDot11StationIPAddress . '.' . $clientSuffix];
                } else {
                    $clientIpAddress = null;
                }
    
                if ( isset($clientDetails[OID_Colubris_coDot11SignalLevel . '.' . $clientSuffix]) ) {
                    $clientSignalStrength = $clientDetails[OID_Colubris_coDot11SignalLevel . '.' . $clientSuffix];
                } else {
                    $clientSignalStrength = null;
                }
    
                if ($bssid) {
                    // found bssid
                    $_ret[] =  [ 'table'  => 'bds',
                                 'action' => 'insert',
                                 'key'    => [ 'node_id'  => $this->node->id,
                                               'bssid_id' => $bssid->id,
                                               'clientMacAddress' => $clientMacAddress,
                                               'timestamp' => \Carbon\Carbon::now(),
                                               ],
        
                                 'data'   => [ 'clientIpAddress'      => $clientIpAddress,
                                               'clientSignalStrength' => $clientSignalStrength,
                                               'clientBytesReceived'  => 0,
                                               'clientBytesSent'      => 0,
                                               ],
    
    
                                ];
                }
            }
        }

        return $_ret;
    }
}
