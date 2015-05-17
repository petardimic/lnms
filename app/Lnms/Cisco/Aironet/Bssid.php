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
     * poller bssidName
     *
     * @return Array
     */
    public function poll_bssidName() {

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
                // 'port_id' => $port->id,

                $_ret[] =  [ 'table'  => 'bssids',
                             'action' => 'sync',
                             'key'    => [ 'node_id' => $this->node->id,
                                           'bssidIndex' => $bssidIndex,
                                           ],
    
                             'data'   => [ 'bssidMacAddress'     => $port->ifPhysAddress,
                                           'bssidName'           => $bssidName,
                                           'bssidSpec'           => $bssidSpec,
                                           'bssidMaxRate'        => $bssidMaxRate,
                                           'bssidCurrentChannel' => $bssidCurrentChannel,
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

        // walk cDot11ClientIpAddress
        $walk_cDot11ClientIpAddress = $snmp->walk(OID_cDot11ClientIpAddress);

        if (count($walk_cDot11ClientIpAddress) == 0) {
            // not found cDot11ClientIpAddress
            return $_ret;
        }

        $walk1 = $snmp->walk(OID_cDot11ClientSignalStrength);

        // oid.?.??.bssidNameDec.clientMacAddressDec = clientIpAddressHex
        foreach ($walk_cDot11ClientIpAddress as $key1 => $clientIpAddressHex) {

            $clientSuffix = str_replace(OID_cDot11ClientIpAddress . '.', '', $key1);
            $tmp1 = explode('.', $clientSuffix);

            // find which bssidName each client connected
            // TODO : for now ignore the first 2-bytes

            // find bssidName
            $bssidName   = '';
            for ($i=2; $i<(count($tmp1)-6); $i++) {
                $bssidName .= chr($tmp1[$i]);
            }

            // find clientMacAddress
            $clientMacAddress = '';
            for ($i=(count($tmp1)-6); $i<count($tmp1); $i++) {
                $clientMacAddress .= sprintf("%02s", dechex($tmp1[$i])) . ':';
            }
            $clientMacAddress = trim($clientMacAddress, ':');

            // find clientIpAddress
            $tmp2 = explode(' ', trim($clientIpAddressHex));
            $clientIpAddress = '';
            for ($i=0; $i<count($tmp2); $i++) {
                $clientIpAddress .= hexdec($tmp2[$i]) . '.';
            }
            $clientIpAddress = trim($clientIpAddress, '.');

            // get cDot11ClientStatisticEntry
            $walk_clientStatistics = $snmp->get([ OID_cDot11ClientSignalStrength . '.' . $clientSuffix,
                                                  OID_cDot11ClientBytesReceived  . '.' . $clientSuffix,
                                                  OID_cDot11ClientBytesSent      . '.' . $clientSuffix,
                                                ]);
            if ( isset($walk_clientStatistics[OID_cDot11ClientSignalStrength . '.' . $clientSuffix]) ) {
                $clientSignalStrength = $walk_clientStatistics[OID_cDot11ClientSignalStrength . '.' . $clientSuffix];
            } else {
                $clientSignalStrength = null;
            }

            if ( isset($walk_clientStatistics[OID_cDot11ClientBytesReceived . '.' . $clientSuffix]) ) {
                $clientBytesReceived = $walk_clientStatistics[OID_cDot11ClientBytesReceived . '.' . $clientSuffix];
            } else {
                $clientBytesReceived = null;
            }

            if ( isset($walk_clientStatistics[OID_cDot11ClientBytesSent . '.' . $clientSuffix]) ) {
                $clientBytesSent = $walk_clientStatistics[OID_cDot11ClientBytesSent . '.' . $clientSuffix];
            } else {
                $clientBytesSent = null;
            }

            // find bssid
            $bssid = \App\Bssid::where('node_id', $this->node->id)
                              ->where('bssidName', $bssidName)
                              ->first();

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
                                           'clientBytesReceived'  => $clientBytesReceived,
                                           'clientBytesSent'      => $clientBytesSent,
                                           ],


                            ];
            }
        }

        return $_ret;
    }
}
