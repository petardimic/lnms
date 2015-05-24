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
                                     OID_Aruba_wlanAPUpTime . '.' . $apSuffix,
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
                $apStatus = '0';
            } else {
                if ( $apDetails[OID_Aruba_wlanAPStatus . '.' . $apSuffix] == 1 ) {
                    $apStatus = 100;
                } else {
                    $apStatus = 0;
                }
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

            // AP UpTime
            if ($apDetails[OID_Aruba_wlanAPUpTime . '.' . $apSuffix] === false) {
                $apUpTime = 0;
                $snmp_success = 0;
            } else {
                $apUpTime = $apDetails[OID_Aruba_wlanAPUpTime . '.' . $apSuffix];

                if ($apUpTime > 0) {
                    $snmp_success = 100;
                } else {
                    $snmp_success = 0;
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
                                       'ping_updated'   => \Carbon\Carbon::now(),
                                       'snmp_success'   => $snmp_success,
                                       'snmp_updated'   => \Carbon\Carbon::now(),
                                       'sysUpTime'      => $apUpTime,
                                       'poll_class'     => 'Aruba\\Ap',
                                       'poll_enabled'   => 'N',
                                       'project_id'     => $this->node->project_id,
                                       ],
                        ];

            // pings
            if ( \App\Node::where('ip_address', $apIpAddress)->count() == 1 ) {
                $_ret[] =  [ 'table'  => 'pings',
                             'action' => 'insert',
                             'data'   => [ 'node_id'    => \App\Node::where('ip_address', $apIpAddress)->first()->id,
                                           'success'    => $apStatus,
                                           'timestamp'  => \Carbon\Carbon::now(),
                                         ]
                            ];
            }
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
            $bssidDetails = $snmp->get([OID_Aruba_wlanAPESSID   . '.' . $bssidSuffix,
                                        OID_Aruba_wlanAPRadioType . '.' . $apMacDec . '.' . $bssidIndex,
                                        OID_Aruba_wlanAPRadioNumAssociatedClients . '.' . $apMacDec . '.' . $bssidIndex,
                                        OID_Aruba_wlanAPTxBytes . '.' . $bssidSuffix,
                                        OID_Aruba_wlanAPRxBytes . '.' . $bssidSuffix,
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

            // bssidClients_count
            if ($bssidDetails[OID_Aruba_wlanAPRadioNumAssociatedClients . '.' . $apMacDec . '.' . $bssidIndex] === false) {
                $bssidClients_count = '';
            } else {
                $bssidClients_count = $bssidDetails[OID_Aruba_wlanAPRadioNumAssociatedClients . '.' . $apMacDec . '.' . $bssidIndex];
            }

            // OID_Aruba_wlanAPTxBytes
            if ($bssidDetails[OID_Aruba_wlanAPTxBytes . '.' . $bssidSuffix] === false) {
                $bssidTxBytes = '';
            } else {
                $bssidTxBytes = $bssidDetails[OID_Aruba_wlanAPTxBytes . '.' . $bssidSuffix];
            }

            // OID_Aruba_wlanAPRxBytes
            if ($bssidDetails[OID_Aruba_wlanAPRxBytes . '.' . $bssidSuffix] === false) {
                $bssidRxBytes = '';
            } else {
                $bssidRxBytes = $bssidDetails[OID_Aruba_wlanAPRxBytes . '.' . $bssidSuffix];
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
                                           'bssidClients_count'    => $bssidClients_count,
                                           'bssidClients_updated'  => \Carbon\Carbon::now(),
                                           ],
                            ];

                if (\App\Bssid::where('bssidIndex', $bssidIndex)->first()) {

                    // insert
                    $_ret[] =  [ 'table'  => 'bds',
                                 'action' => 'insert',
                                 'key'    => [ 'node_id'  => $apNode->id,
                                               'bssid_id' => \App\Bssid::where('bssidIndex', $bssidIndex)->first()->id,
                                               'clientMacAddress' => $bssidMacAddress,
                                               'timestamp' => \Carbon\Carbon::now(),
                                               ],
        
                                 'data'   => [ 'clientIpAddress'      => '',
                                               'clientSignalStrength' => '',
                                               'clientUserAgent'      => '',
                                               'clientUserType'       => '',
                                               'clientBytesReceived'  => $bssidRxBytes,
                                               'clientBytesSent'      => $bssidTxBytes,
                                               ],
                                ];

                    print \App\Bssid::where('bssidIndex', $bssidIndex)->first()->id . ' count=' . count($_ret) . "\n";

                }
            }
        }

        return $_ret;
    }

    /**
     * poller ap client
     *
     * @return Array
     */
    public function poll_apClient() {

        // return
        $_ret = [];

        $snmp = new \App\Lnms\Snmp($this->node->ip_address, $this->node->snmp_comm_ro);

        $walk_nUserApBSSID    = $snmp->walk(OID_Aruba_nUserApBSSID);

        if ( count($walk_nUserApBSSID) == 0 ) {
            return $_ret;
        }

        // oid.clientMacDec(6).clientIpAddress(4) = bssidMacHex
        foreach ($walk_nUserApBSSID as $key1 => $bssidMacHex) {

            $bssidMacAddress = str_replace(' ', ':', trim($bssidMacHex));
            $clientSuffix = str_replace(OID_Aruba_nUserApBSSID . '.', '', $key1);
            $tmp1 = explode('.', $clientSuffix);

            $clientMacAddress = sprintf("%02s", dechex($tmp1[0])) . ':'
                               . sprintf("%02s", dechex($tmp1[1])) . ':'
                               . sprintf("%02s", dechex($tmp1[2])) . ':'
                               . sprintf("%02s", dechex($tmp1[3])) . ':'
                               . sprintf("%02s", dechex($tmp1[4])) . ':'
                               . sprintf("%02s", dechex($tmp1[5]));

            $clientIpAddress = $tmp1[6] . '.' . $tmp1[7] . '.' . $tmp1[8] . '.' . $tmp1[9];

            // find bssid
            $bssid = \App\Bssid::where('bssidMacAddress', $bssidMacAddress)
                               ->first();

            $clientSignalStrength = 0;
            $clientBytesReceived  = 0;
            $clientBytesSent      = 0;

            // get more details about client
            $clientDetails = $snmp->get([ OID_Aruba_staUserAgent . '.' . $clientSuffix,
                                          OID_Aruba_staUserType  . '.' . $clientSuffix
                                        ]);

            if ( isset($clientDetails[OID_Aruba_staUserAgent . '.' . $clientSuffix]) ) {
                $clientUserAgent = $clientDetails[OID_Aruba_staUserAgent . '.' . $clientSuffix];
            } else {
                $clientUserAgent = '';
            }

            if ( isset($clientDetails[OID_Aruba_staUserType . '.' . $clientSuffix]) ) {
                $clientUserType = $clientDetails[OID_Aruba_staUserType . '.' . $clientSuffix];
            } else {
                $clientUserType = '';
            }

            if ($bssid) {
                // found bssid
                $_ret[] =  [ 'table'  => 'bds',
                             'action' => 'insert',
                             'key'    => [ 'node_id'  => $bssid->node->id,
                                           'bssid_id' => $bssid->id,
                                           'clientMacAddress' => $clientMacAddress,
                                           'timestamp' => \Carbon\Carbon::now(),
                                           ],
    
                             'data'   => [ 'clientIpAddress'      => $clientIpAddress,
                                           'clientSignalStrength' => $clientSignalStrength,
                                           'clientBytesReceived'  => $clientBytesReceived,
                                           'clientBytesSent'      => $clientBytesSent,
                                           'clientUserAgent'      => $clientUserAgent,
                                           'clientUserType'       => $clientUserType,
                                           ],
                            ];
            }
        }

        return $_ret;
    }
}
