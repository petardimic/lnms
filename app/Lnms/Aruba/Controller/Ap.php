<?php namespace App\Lnms\Aruba\Controller;

class Ap {

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
     * poller ap
     *
     * @return Array
     */
    public function poll_apAddress() {

        // return
        $_ret = [];

        $snmp = new \App\Lnms\Snmp($this->node->ip_address, $this->node->snmp_comm_ro);

        // walk Aruba_wlanAPIpAddress
        $walk_Aruba_wlanAPIpAddress = $snmp->walk(OID_Aruba_wlanAPIpAddress);

        if ( count($walk_Aruba_wlanAPIpAddress) == 0 ) {
            return $_ret;
        }

        // oid.apMacDec(6) = apIpAddress

        foreach ($walk_Aruba_wlanAPIpAddress as $key1 => $apIpAddress) {

            $apSuffix = str_replace(OID_Aruba_wlanAPIpAddress . '.', '', $key1);

            $tmp1 = explode('.', $apSuffix);
            $apMacAddress = sprintf("%02s", dechex($tmp1[0])) . ':'
                            . sprintf("%02s", dechex($tmp1[1])) . ':'
                            . sprintf("%02s", dechex($tmp1[2])) . ':'
                            . sprintf("%02s", dechex($tmp1[3])) . ':'
                            . sprintf("%02s", dechex($tmp1[4])) . ':'
                            . sprintf("%02s", dechex($tmp1[5]));


            // get more details about Ap
            $apDetails = $snmp->get([OID_Aruba_wlanAPName   . '.' . $apSuffix,
                                     OID_Aruba_wlanAPStatus . '.' . $apSuffix,
                                     OID_Aruba_wlanAPOobIpAddress . '.' . $apSuffix,
                                    ]);
            // AP Name
            if ($apDetails[OID_Aruba_wlanAPName . '.' . $apSuffix] === false) {
                $apName = '';
            } else {
                $apName = $apDetails[OID_Aruba_wlanAPName . '.' . $apSuffix];
            }

            // AP Status
            if ($apDetails[OID_Aruba_wlanAPStatus . '.' . $apSuffix] === false) {
                $apStatus = '';
            } else {
                $apStatus = $apDetails[OID_Aruba_wlanAPStatus . '.' . $apSuffix];
            }

            // AP Oob IP Addess
            if ($apDetails[OID_Aruba_wlanAPOobIpAddress . '.' . $apSuffix] === false) {
                $apOobIpAddress = '';
            } else {
                $apOobIpAddress = $apDetails[OID_Aruba_wlanAPOobIpAddress . '.' . $apSuffix];

                if ($apOobIpAddress <> '') {
                    $apIpAddress = $apOobIpAddress;
                }
            }

            // print "$apMacAddress $apIpAddress<br>";
            $_ret[] =  [ 'table'  => 'nodes',
                         'action' => 'sync',
                         'key'    => [ 'ip_address' => $apIpAddress,
                                       ],

                         'data'   => [ 'name'           => $apName,
                                       'mac_address'    => $apMacAddress,
                                       'ping_method'    => 'Aruba\Controller\Ap::poll_apAddress',
                                       'ping_params'    => $this->node->id,
                                       'ping_success'   => $apStatus,
                                       ],
                        ];
        }

        return $_ret;
    }

    /**
     * poller ap bssid
     *
     * @return Array
     */
    public function poll_apBssid() {

        // return
        $_ret = [];

        $snmp = new \App\Lnms\Snmp($this->node->ip_address, $this->node->snmp_comm_ro);

        // oid.apMacDec(6).bssidIndex.bssidMacDec(6) = bssidCurrentChannel
        $walk_wlanAPCurrentChannel    = $snmp->walk(OID_Aruba_wlanAPCurrentChannel);

        if ( count($walk_wlanAPCurrentChannel) == 0 ) {
            return $_ret;
        }

        foreach ($walk_wlanAPCurrentChannel as $key1 => $bssidCurrentChannel) {
            $bssidSuffix = str_replace(OID_Aruba_wlanAPCurrentChannel . '.', '', $key1);

            $tmp1 = explode('.', $bssidSuffix);

            $apMacDec = $tmp1[0] . '.' . $tmp1[1] . '.' . $tmp1[2] . '.'
                      . $tmp1[3] . '.' . $tmp1[4] . '.' . $tmp1[5];

            $apMacAddress = sprintf("%02s", dechex($tmp1[0])) . ':'
                            . sprintf("%02s", dechex($tmp1[1])) . ':'
                            . sprintf("%02s", dechex($tmp1[2])) . ':'
                            . sprintf("%02s", dechex($tmp1[3])) . ':'
                            . sprintf("%02s", dechex($tmp1[4])) . ':'
                            . sprintf("%02s", dechex($tmp1[5]));

            $bssidIndex = $tmp1[6];

            $bssidMacAddress = sprintf("%02s", dechex($tmp1[7])) . ':'
                               . sprintf("%02s", dechex($tmp1[8])) . ':'
                               . sprintf("%02s", dechex($tmp1[9])) . ':'
                               . sprintf("%02s", dechex($tmp1[10])) . ':'
                               . sprintf("%02s", dechex($tmp1[11])) . ':'
                               . sprintf("%02s", dechex($tmp1[12]));

            // get more details about bssid
            $bssidDetails = $snmp->get([OID_Aruba_wlanAPESSID     . '.' . $bssidSuffix,
                                        OID_Aruba_wlanAPRadioType . '.' . $apMacDec . '.' . $bssidIndex,
                                        ]);
            // bssidName
            if ($bssidDetails[OID_Aruba_wlanAPESSID . '.' . $bssidSuffix] === false) {
                $bssidName = '';
            } else {
                $bssidName = $bssidDetails[OID_Aruba_wlanAPESSID . '.' . $bssidSuffix];
            }

            // bssidSpec
            if ($bssidDetails[OID_Aruba_wlanAPRadioType . '.' . $apMacDec . '.' . $bssidIndex] === false) {
                $bssidSpec = '';
            } else {
                $bssidSpec = $bssidDetails[OID_Aruba_wlanAPRadioType . '.' . $apMacDec . '.' . $bssidIndex];
            }

            //
            $apNode = \App\Node::where('mac_address', $apMacAddress)
                               ->first();

            if ($apNode) {
                // found apNode

                $_ret[] =  [ 'table'  => 'bssids',
                             'action' => 'sync',
                             'key'    => [ 'node_id' => $apNode->id,
                                           'bssidIndex' => $bssidIndex,
                                           ],
    
                             'data'   => [ 'bssidMacAddress'     => $bssidMacAddress,
                                           'bssidName'           => $bssidName,
                                           'bssidSpec'           => $bssidSpec,
                                           'bssidMaxRate'        => 0,
                                           'bssidCurrentChannel' => $bssidCurrentChannel,
                                           ],
                            ];
            }
        }

        return $_ret;
    }
}
