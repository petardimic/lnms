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
}
