<?php namespace App\Lnms;

/*
 * SNMP Class
 *  - using net-snmp 5.5 commands
 *  - tested on CentOS 6
 */

class Snmp {

    /*
     * SNMP Agent's IP Address
     */
    protected $ip_address;

    /*
     * SNMP Version
     */
    protected $snmp_version;

    /*
     * SNMP Community String (read only)
     */
    protected $snmp_comm_ro;

    /*
     * SNMP Command Output Options
     * Description (net-snmp):
     *    0:  print leading 0 for single-digit hex characters
     *    e:  print enums numerically
     *    f:  print full OIDs on output
     *    n:  print OIDs numerically
     *    q:  quick print for easier parsing
     *    t:  print timeticks unparsed as numeric integers
     */
    protected $output_options = '0efnqt';

    /**
     * set other snmp command options
     */
    protected $options = '';

    /*
     * SNMP Get Command
     */
    protected $snmpget;

    /*
     * SNMP Walk Command
     */
    protected $snmpwalk;

    /*
     * SNMP error
     */
    protected $errno;
    protected $error;

    /*
     * initial variables
     */
    public function __construct($ip_address, $snmp_comm_ro, $snmp_version='1') {
        $this->ip_address   = $ip_address;
        $this->snmp_comm_ro = $snmp_comm_ro;
        $this->snmp_comm_ro = $snmp_comm_ro;
        $this->snmp_version = $snmp_version;

//        // SNMP Get Command
//        $this->snmpget = 'snmpget -O ' . $this->output_options
//                           . ' ' . $this->options
//                           . ' -v ' . $snmp_version
//                           . ' -c ' . $snmp_comm_ro . ' ' . $ip_address ;
//
//        // SNMP Walk Command
//        $this->snmpwalk = 'snmpwalk -O ' . $this->output_options
//                           . ' ' . $this->options
//                           . ' -v ' . $snmp_version
//                           . ' -c ' . $snmp_comm_ro . ' ' . $ip_address ;

    }

    /**
     * set other snmp options
     */
    public function setOptions($options) {
        $this->options = $options;
    }

    /*
     * run snmpget
     */
    public function get($oids) {

        if ( ! is_array($oids) ) {
            $oids = [ $oids ];
        }

        // SNMP Get Command
        $this->snmpget = 'snmpget -O ' . $this->output_options
                           . ' ' . $this->options
                           . ' -v ' . $this->snmp_version
                           . ' -c ' . $this->snmp_comm_ro . ' ' . $this->ip_address ;

        /**
         * Maximum oids to snmpget in the same time
         */
        if ( ! defined('SNMPGET_MAX_OIDS') ) {
            define('SNMPGET_MAX_OIDS', '10');
        }

        if ( count($oids) > SNMPGET_MAX_OIDS ) {
            // targets to fping
            $chunks = array_chunk($oids, SNMPGET_MAX_OIDS);
        } else {
            $chunks = [];
            $chunks[] = $oids;
        }

        // clear last error before snmpget
        $this->error = '';

        foreach ($chunks as $chunk) {
            $oids = implode(' ', $chunk);
            exec("$this->snmpget $oids 2>&1", $exec_out1, $exec_ret1);
        }

        return $this->output($exec_out1, $exec_ret1);
    }

    /*
     * run snmpwalk
     */
    public function walk($oid) {

        // SNMP Walk Command
        $this->snmpwalk = 'snmpwalk -O ' . $this->output_options
                           . ' ' . $this->options
                           . ' -v ' . $this->snmp_version
                           . ' -c ' . $this->snmp_comm_ro . ' ' . $this->ip_address;

        // clear last error before snmpget again
        $this->error = '';
        exec("$this->snmpwalk $oid 2>&1", $exec_out1, $exec_ret1);

        return $this->output($exec_out1, $exec_ret1);
    }

    /*
     * re-format output, and handle error messages
     *  - return output to associate array
     *    ex: [.1.3.6.1.2.1.1.3.0] => 14837089
     *
     * snmp commands return number
     *  0:  SNMP Ok
     *
     *  2:  Error in packet, noSuchName, Failed Object
     *      Note: If get many oids, some oid value may return ok
     * 
     *      Examples:
     *      $ get .1.3.6.1.2.1.1.3.0 .1.3.6.1.2.1.1.99
     *      Error in packet
     *      Reason: (noSuchName) There is no such variable name in this MIB.
     *      Failed object: .1.3.6.1.2.1.1.99
     *      .1.3.6.1.2.1.1.3.0 5040854
     * 
     * 1:   SNMP Timeout: No Response from ...
     * 127: command not found
     *
     */
    public function output($exec_out1, $exec_ret1) {

        // store return status in errno
        $this->errno = $exec_ret1;

        if ( $exec_ret1 == 0 || $exec_ret1 == 2 ) {
            $_ret = array();
            for ($i=0; $i<count($exec_out1); $i++) {
                if ( preg_match('/^\./', $exec_out1[$i]) ) {
                    // oid ok
                    $oid   = preg_replace('/^([^\ ]+) *.*/', '\\1', $exec_out1[$i]);
                    $value = preg_replace('/' . $oid . ' */', '', $exec_out1[$i]); 

                    // remove begin and end ", and trim space at the end of line
                    $value = preg_replace('/^"/', '', $value);
                    $value = preg_replace('/"$/', '', $value);
                    $value = trim($value);

                    $_ret[$oid] = $value;
                } elseif ( preg_match('/^Failed object:/', $exec_out1[$i]) ) {
                    // oid fail
                    $oid = str_replace('Failed object: ', '', $exec_out1[$i]);
                    $value = false;
                    $_ret[$oid] = $value;
                    $this->error .= $exec_out1[$i] . PHP_EOL;
                } else {
                    // store error messages in error
                    $this->error .= $exec_out1[$i] . PHP_EOL;
                }
            }
            return $_ret;
        }

        // store error messages in error
        for ($i=0; $i<count($exec_out1); $i++) {
            $this->error .= $exec_out1[$i] . PHP_EOL;
        }

        return false;
    }

    /*
     * get last error return number
     */
    public function getErrno() {
        return $this->errno;
    }

    /*
     * get last error message
     */ 
    public function getError() {
        return $this->error;
    }
}

//------------------------------------------------------------------------------
// MIBs
//------------------------------------------------------------------------------
// system
define('OID_sysDescr',      '.1.3.6.1.2.1.1.1.0');
define('OID_sysObjectID',   '.1.3.6.1.2.1.1.2.0');
define('OID_sysUpTime',     '.1.3.6.1.2.1.1.3.0');
define('OID_sysContact',    '.1.3.6.1.2.1.1.4.0');
define('OID_sysName',       '.1.3.6.1.2.1.1.5.0');
define('OID_sysLocation',   '.1.3.6.1.2.1.1.6.0');

// ifEntry
define('OID_ifIndex',           '.1.3.6.1.2.1.2.2.1.1');
define('OID_ifDescr',           '.1.3.6.1.2.1.2.2.1.2');
define('OID_ifType',            '.1.3.6.1.2.1.2.2.1.3');
define('OID_ifMtu',             '.1.3.6.1.2.1.2.2.1.4');
define('OID_ifSpeed',           '.1.3.6.1.2.1.2.2.1.5');
define('OID_ifPhysAddress',     '.1.3.6.1.2.1.2.2.1.6');
define('OID_ifAdminStatus',     '.1.3.6.1.2.1.2.2.1.7');
define('OID_ifOperStatus',      '.1.3.6.1.2.1.2.2.1.8');
define('OID_ifLastChange',      '.1.3.6.1.2.1.2.2.1.9');
define('OID_ifInOctets',        '.1.3.6.1.2.1.2.2.1.10');
define('OID_ifInUcastPkts',     '.1.3.6.1.2.1.2.2.1.11');
define('OID_ifInNUcastPkts',    '.1.3.6.1.2.1.2.2.1.12');
define('OID_ifInDiscards',      '.1.3.6.1.2.1.2.2.1.13');
define('OID_ifInErrors',        '.1.3.6.1.2.1.2.2.1.14');
define('OID_ifInUnknownProtos', '.1.3.6.1.2.1.2.2.1.15');
define('OID_ifOutOctets',       '.1.3.6.1.2.1.2.2.1.16');
define('OID_ifOutUcastPkts',    '.1.3.6.1.2.1.2.2.1.17');
define('OID_ifOutNUcastPkts',   '.1.3.6.1.2.1.2.2.1.18');
define('OID_ifOutDiscards',     '.1.3.6.1.2.1.2.2.1.19');
define('OID_ifOutErrors',       '.1.3.6.1.2.1.2.2.1.20');
define('OID_ifOutQLen',         '.1.3.6.1.2.1.2.2.1.21');
define('OID_ifSpecific',        '.1.3.6.1.2.1.2.2.1.22');

// ifXEntry
define('OID_ifName',                    '.1.3.6.1.2.1.31.1.1.1.1');
define('OID_ifInMulticastPkts',         '.1.3.6.1.2.1.31.1.1.1.2');
define('OID_ifInBroadcastPkts',         '.1.3.6.1.2.1.31.1.1.1.3');
define('OID_ifOutMulticastPkts',        '.1.3.6.1.2.1.31.1.1.1.4');
define('OID_ifOutBroadcastPkts',        '.1.3.6.1.2.1.31.1.1.1.5');
define('OID_ifHCInOctets',              '.1.3.6.1.2.1.31.1.1.1.6');
define('OID_ifHCInUcastPkts',           '.1.3.6.1.2.1.31.1.1.1.7');
define('OID_ifHCInMulticastPkts',       '.1.3.6.1.2.1.31.1.1.1.8');
define('OID_ifHCInBroadcastPkts',       '.1.3.6.1.2.1.31.1.1.1.9');
define('OID_ifHCOutOctets',             '.1.3.6.1.2.1.31.1.1.1.10');
define('OID_ifHCOutUcastPkts',          '.1.3.6.1.2.1.31.1.1.1.11');
define('OID_ifHCOutMulticastPkts',      '.1.3.6.1.2.1.31.1.1.1.12');
define('OID_ifHCOutBroadcastPkts',      '.1.3.6.1.2.1.31.1.1.1.13');
define('OID_ifLinkUpDownTrapEnable',    '.1.3.6.1.2.1.31.1.1.1.14');
define('OID_ifHighSpeed',               '.1.3.6.1.2.1.31.1.1.1.15');
define('OID_ifPromiscuousMode',         '.1.3.6.1.2.1.31.1.1.1.16');
define('OID_ifConnectorPresent',        '.1.3.6.1.2.1.31.1.1.1.17');
define('OID_ifAlias',                   '.1.3.6.1.2.1.31.1.1.1.18');
define('OID_ifCounterDiscontinuityTime','.1.3.6.1.2.1.31.1.1.1.19');

// vlan (only static)
define('OID_dot1qVlanStaticName',           '.1.3.6.1.2.1.17.7.1.4.3.1.1');
define('OID_dot1qVlanStaticEgressPorts',    '.1.3.6.1.2.1.17.7.1.4.3.1.2');
define('OID_dot1qVlanForbiddenEgressPorts', '.1.3.6.1.2.1.17.7.1.4.3.1.3');
define('OID_dot1qVlanStaticUntaggedPorts',  '.1.3.6.1.2.1.17.7.1.4.3.1.4');
define('OID_dot1qVlanStaticRowStatus',      '.1.3.6.1.2.1.17.7.1.4.3.1.5');

// vlan (all)
define('OID_dot1qVlanFdbId',                '.1.3.6.1.2.1.17.7.1.4.2.1.3');
define('OID_dot1qVlanCurrentEgressPorts',   '.1.3.6.1.2.1.17.7.1.4.2.1.4');
define('OID_dot1qVlanCurrentUntaggedPorts', '.1.3.6.1.2.1.17.7.1.4.2.1.5');
define('OID_dot1qVlanStatus',               '.1.3.6.1.2.1.17.7.1.4.2.1.6'); // 1 : other 2 : permanent 3 : dynamicGvrp
define('OID_dot1qVlanCreationTime',         '.1.3.6.1.2.1.17.7.1.4.2.1.7');

// map between portIndex and ifIndex
define('OID_dot1dBasePortIfIndex',  '.1.3.6.1.2.1.17.1.4.1.2');

// oid.vlanIndex.macDec(6) = portIndex
define('OID_dot1qTpFdbPort',    '.1.3.6.1.2.1.17.7.1.2.2.1.2');

//  ipAdEntAddr
define('OID_ipAdEntAddr',       '.1.3.6.1.2.1.4.20.1.1');   // oid.ipAddress(4) = ipAddress
define('OID_ipAdEntIfIndex',    '.1.3.6.1.2.1.4.20.1.2');   // oid.ipAddress(4) = ifIndex
define('OID_ipAdEntNetMask',    '.1.3.6.1.2.1.4.20.1.3');   // oid.ipAddress(4) = netmask

// ipNetToMedia
define('OID_ipNetToMediaIfIndex',       '.1.3.6.1.2.1.4.22.1.1');   // oid.ifIndex.ipAddress(4) = ifIndex
define('OID_ipNetToMediaPhysAddress',   '.1.3.6.1.2.1.4.22.1.2');   // oid.ifIndex.ipAddress(4) = macAddress
define('OID_ipNetToMediaNetAddress',    '.1.3.6.1.2.1.4.22.1.3');   // oid.ifIndex.ipAddress(4) = ipAddress
define('OID_ipNetToMediaType',          '.1.3.6.1.2.1.4.22.1.4');   // oid.ifIndex.ipAddress(4) = arpType

// ipRoute
define('OID_ipRouteIfIndex',    '.1.3.6.1.2.1.4.21.1.2');   // oid.routeDest(4) = ifIndex
define('OID_ipRouteNextHop',    '.1.3.6.1.2.1.4.21.1.7');   // oid.routeDest(4) = routeNextHop
define('OID_ipRouteType',       '.1.3.6.1.2.1.4.21.1.8');   // oid.routeDest(4) = routeType
define('OID_ipRouteProto',      '.1.3.6.1.2.1.4.21.1.9');   // oid.routeDest(4) = routeProto
define('OID_ipRouteMask',       '.1.3.6.1.2.1.4.21.1.11');  // oid.routeDest(4) = routeMasks

// Cisco Aironet BSSIDs
define('OID_cd11IfAuxSsid',                     '.1.3.6.1.4.1.9.9.272.1.1.1.6.1.2');
define('OID_cd11IfPhyMacSpecification',         '.1.3.6.1.4.1.9.9.272.1.1.2.1.1.6');    // 1 = 11a, 2 = 11b, 3 = 11g
define('OID_cd11IfPhyDsssMaxCompatibleRate',    '.1.3.6.1.4.1.9.9.272.1.1.2.5.1.1');    // 500 Kb per second
define('OID_cd11IfPhyDsssCurrentChannel',       '.1.3.6.1.4.1.9.9.272.1.1.2.5.1.3');

// Cisco Aironet Clients
define('OID_cDot11ClientIpAddress',         '.1.3.6.1.4.1.9.9.273.1.2.1.1.16');
define('OID_cDot11ClientSignalStrength',    '.1.3.6.1.4.1.9.9.273.1.3.1.1.3');
define('OID_cDot11ClientBytesReceived',     '.1.3.6.1.4.1.9.9.273.1.3.1.1.7');
define('OID_cDot11ClientBytesSent',         '.1.3.6.1.4.1.9.9.273.1.3.1.1.9');

// dot1dTpFdb
define('OID_dot1dTpFdbAddress',             '.1.3.6.1.2.1.17.4.3.1.1');
define('OID_dot1dTpFdbPort',                '.1.3.6.1.2.1.17.4.3.1.2'); // oid.macDec(6) = portIndex
define('OID_dot1dTpFdbStatus',              '.1.3.6.1.2.1.17.4.3.1.3');

// Aruba wlsxWlanAP
define('OID_Aruba_wlanAPIpAddress',           '.1.3.6.1.4.1.14823.2.2.1.5.2.1.4.1.2');  // oid.macDec(6) = ipAddress
define('OID_Aruba_wlanAPName',                '.1.3.6.1.4.1.14823.2.2.1.5.2.1.4.1.3');
define('OID_Aruba_wlanAPGroupName',           '.1.3.6.1.4.1.14823.2.2.1.5.2.1.4.1.4');
define('OID_Aruba_wlanAPModel',               '.1.3.6.1.4.1.14823.2.2.1.5.2.1.4.1.5');
define('OID_Aruba_wlanAPSerialNumber',        '.1.3.6.1.4.1.14823.2.2.1.5.2.1.4.1.6');
define('OID_Aruba_wlanAPdot11aAntennaGain',   '.1.3.6.1.4.1.14823.2.2.1.5.2.1.4.1.7');
define('OID_Aruba_wlanAPdot11gAntennaGain',   '.1.3.6.1.4.1.14823.2.2.1.5.2.1.4.1.8');
define('OID_Aruba_wlanAPNumRadios',           '.1.3.6.1.4.1.14823.2.2.1.5.2.1.4.1.9');
define('OID_Aruba_wlanAPEnet1Mode',           '.1.3.6.1.4.1.14823.2.2.1.5.2.1.4.1.10');
define('OID_Aruba_wlanAPIpsecMode',           '.1.3.6.1.4.1.14823.2.2.1.5.2.1.4.1.11');
define('OID_Aruba_wlanAPUpTime',              '.1.3.6.1.4.1.14823.2.2.1.5.2.1.4.1.12');
define('OID_Aruba_wlanAPModelName',           '.1.3.6.1.4.1.14823.2.2.1.5.2.1.4.1.13');
define('OID_Aruba_wlanAPLocation',            '.1.3.6.1.4.1.14823.2.2.1.5.2.1.4.1.14');
define('OID_Aruba_wlanAPBuilding',            '.1.3.6.1.4.1.14823.2.2.1.5.2.1.4.1.15');
define('OID_Aruba_wlanAPFloor',               '.1.3.6.1.4.1.14823.2.2.1.5.2.1.4.1.16');
define('OID_Aruba_wlanAPLoc',                 '.1.3.6.1.4.1.14823.2.2.1.5.2.1.4.1.17');
define('OID_Aruba_wlanAPExternalAntenna',     '.1.3.6.1.4.1.14823.2.2.1.5.2.1.4.1.18');
define('OID_Aruba_wlanAPStatus',              '.1.3.6.1.4.1.14823.2.2.1.5.2.1.4.1.19');
define('OID_Aruba_wlanAPNumBootstraps',       '.1.3.6.1.4.1.14823.2.2.1.5.2.1.4.1.20');
define('OID_Aruba_wlanAPNumReboots',          '.1.3.6.1.4.1.14823.2.2.1.5.2.1.4.1.21');
define('OID_Aruba_wlanAPUnprovisioned',       '.1.3.6.1.4.1.14823.2.2.1.5.2.1.4.1.22');
define('OID_Aruba_wlanAPMonitorMode',         '.1.3.6.1.4.1.14823.2.2.1.5.2.1.4.1.23');
define('OID_Aruba_wlanAPFQLNBuilding',        '.1.3.6.1.4.1.14823.2.2.1.5.2.1.4.1.24');
define('OID_Aruba_wlanAPFQLNFloor',           '.1.3.6.1.4.1.14823.2.2.1.5.2.1.4.1.25');
define('OID_Aruba_wlanAPFQLN',                '.1.3.6.1.4.1.14823.2.2.1.5.2.1.4.1.26');
define('OID_Aruba_wlanAPFQLNCampus',          '.1.3.6.1.4.1.14823.2.2.1.5.2.1.4.1.27');
define('OID_Aruba_wlanAPOobIpAddress',        '.1.3.6.1.4.1.14823.2.2.1.5.2.1.4.1.36');

// Aruba wlsxSwitchStationMgmt
define('OID_Aruba_staPhyAddress',          '.1.3.6.1.4.1.14823.2.2.1.1.2.2.1.1');
define('OID_Aruba_staAccessPointBSSID',    '.1.3.6.1.4.1.14823.2.2.1.1.2.2.1.2');
define('OID_Aruba_staUserName',            '.1.3.6.1.4.1.14823.2.2.1.1.2.2.1.3');
define('OID_Aruba_staUserRole',            '.1.3.6.1.4.1.14823.2.2.1.1.2.2.1.4');
define('OID_Aruba_staAssociationID',       '.1.3.6.1.4.1.14823.2.2.1.1.2.2.1.5');
define('OID_Aruba_staAccessPointESSID',    '.1.3.6.1.4.1.14823.2.2.1.1.2.2.1.6');
define('OID_Aruba_staSignalToNoiseRatio',  '.1.3.6.1.4.1.14823.2.2.1.1.2.2.1.7');
define('OID_Aruba_staTransmitRate',        '.1.3.6.1.4.1.14823.2.2.1.1.2.2.1.8');
define('OID_Aruba_staReceiveRate',         '.1.3.6.1.4.1.14823.2.2.1.1.2.2.1.9');

// Aruba wlsxSwitchStationStats
define('OID_Aruba_staTxPackets',               '.1.3.6.1.4.1.14823.2.2.1.1.2.3.1.1');
define('OID_Aruba_staTxBytes',                 '.1.3.6.1.4.1.14823.2.2.1.1.2.3.1.2');
define('OID_Aruba_staRxPackets',               '.1.3.6.1.4.1.14823.2.2.1.1.2.3.1.3');
define('OID_Aruba_staRxBytes',                 '.1.3.6.1.4.1.14823.2.2.1.1.2.3.1.4');
define('OID_Aruba_staBwRate',                  '.1.3.6.1.4.1.14823.2.2.1.1.2.3.1.5');
define('OID_Aruba_staFrameRetryRate',          '.1.3.6.1.4.1.14823.2.2.1.1.2.3.1.6');
define('OID_Aruba_staFrameLowSpeedRate',       '.1.3.6.1.4.1.14823.2.2.1.1.2.3.1.7');
define('OID_Aruba_staFrameNonUnicastRate',     '.1.3.6.1.4.1.14823.2.2.1.1.2.3.1.8');
define('OID_Aruba_staFrameFragmentationRate',  '.1.3.6.1.4.1.14823.2.2.1.1.2.3.1.9');
define('OID_Aruba_staFrameReceiveErrorRate',   '.1.3.6.1.4.1.14823.2.2.1.1.2.3.1.10');

// Aruba wlsxUser
define('OID_Aruba_nUserPhyAddress',                '.1.3.6.1.4.1.14823.2.2.1.4.1.2.1.1');
define('OID_Aruba_nUserIpAddress',                 '.1.3.6.1.4.1.14823.2.2.1.4.1.2.1.2');
define('OID_Aruba_nUserName',                      '.1.3.6.1.4.1.14823.2.2.1.4.1.2.1.3');
define('OID_Aruba_nUserRole',                      '.1.3.6.1.4.1.14823.2.2.1.4.1.2.1.4');
define('OID_Aruba_nUserUpTime',                    '.1.3.6.1.4.1.14823.2.2.1.4.1.2.1.5');
define('OID_Aruba_nUserAuthenticationMethod',      '.1.3.6.1.4.1.14823.2.2.1.4.1.2.1.6');
define('OID_Aruba_nUserSubAuthenticationMethod',   '.1.3.6.1.4.1.14823.2.2.1.4.1.2.1.7');
define('OID_Aruba_nUserAuthServerName',            '.1.3.6.1.4.1.14823.2.2.1.4.1.2.1.8');
define('OID_Aruba_nUserExtVPNAddress',             '.1.3.6.1.4.1.14823.2.2.1.4.1.2.1.9');
define('OID_Aruba_nUserApLocation',                '.1.3.6.1.4.1.14823.2.2.1.4.1.2.1.10');
define('OID_Aruba_nUserApBSSID',                   '.1.3.6.1.4.1.14823.2.2.1.4.1.2.1.11');
define('OID_Aruba_nUserIsOnHomeAgent',             '.1.3.6.1.4.1.14823.2.2.1.4.1.2.1.12');
define('OID_Aruba_nUserHomeAgentIpAddress',        '.1.3.6.1.4.1.14823.2.2.1.4.1.2.1.13');
define('OID_Aruba_nUserMobilityStatus',            '.1.3.6.1.4.1.14823.2.2.1.4.1.2.1.14');
define('OID_Aruba_nUserHomeVlan',                  '.1.3.6.1.4.1.14823.2.2.1.4.1.2.1.15');
define('OID_Aruba_nUserDefaultVlan',               '.1.3.6.1.4.1.14823.2.2.1.4.1.2.1.16');
define('OID_Aruba_nUserAssignedVlan',              '.1.3.6.1.4.1.14823.2.2.1.4.1.2.1.17');
define('OID_Aruba_nUserBWContractName',            '.1.3.6.1.4.1.14823.2.2.1.4.1.2.1.18');
define('OID_Aruba_nUserBWContractUsage',           '.1.3.6.1.4.1.14823.2.2.1.4.1.2.1.19');
define('OID_Aruba_nUserBWContractId',              '.1.3.6.1.4.1.14823.2.2.1.4.1.2.1.20');
define('OID_Aruba_nUserIsProxyArpEnabled',         '.1.3.6.1.4.1.14823.2.2.1.4.1.2.1.21');
define('OID_Aruba_nUserCurrentVlan',               '.1.3.6.1.4.1.14823.2.2.1.4.1.2.1.22');
define('OID_Aruba_nUserIsWired',                   '.1.3.6.1.4.1.14823.2.2.1.4.1.2.1.23');
define('OID_Aruba_nUserConnectedSlot',             '.1.3.6.1.4.1.14823.2.2.1.4.1.2.1.24');
define('OID_Aruba_nUserConnectedPort',             '.1.3.6.1.4.1.14823.2.2.1.4.1.2.1.25');
define('OID_Aruba_nUserPhyType',                   '.1.3.6.1.4.1.14823.2.2.1.4.1.2.1.26');
define('OID_Aruba_nUserMobilityDomainName',        '.1.3.6.1.4.1.14823.2.2.1.4.1.2.1.27');
define('OID_Aruba_staUserAgent',                   '.1.3.6.1.4.1.14823.2.2.1.4.1.2.1.37');
define('OID_Aruba_staUserType',                    '.1.3.6.1.4.1.14823.2.2.1.4.1.2.1.39');

// Aruba wlsxWlanAPBssid
define('OID_Aruba_wlanAPBSSID',                        '.1.3.6.1.4.1.14823.2.2.1.5.2.1.7.1.1');
define('OID_Aruba_wlanAPESSID',                        '.1.3.6.1.4.1.14823.2.2.1.5.2.1.7.1.2');
define('OID_Aruba_wlanAPBssidSlot',                    '.1.3.6.1.4.1.14823.2.2.1.5.2.1.7.1.3');
define('OID_Aruba_wlanAPBssidPort',                    '.1.3.6.1.4.1.14823.2.2.1.5.2.1.7.1.4');
define('OID_Aruba_wlanAPBssidPhyType',                 '.1.3.6.1.4.1.14823.2.2.1.5.2.1.7.1.5');
define('OID_Aruba_wlanAPBssidRogueType',               '.1.3.6.1.4.1.14823.2.2.1.5.2.1.7.1.6');
define('OID_Aruba_wlanAPBssidMode',                    '.1.3.6.1.4.1.14823.2.2.1.5.2.1.7.1.7');
define('OID_Aruba_wlanAPBssidChannel',                 '.1.3.6.1.4.1.14823.2.2.1.5.2.1.7.1.8');
define('OID_Aruba_wlanAPBssidUpTime',                  '.1.3.6.1.4.1.14823.2.2.1.5.2.1.7.1.9');
define('OID_Aruba_wlanAPBssidInactiveTime',            '.1.3.6.1.4.1.14823.2.2.1.5.2.1.7.1.10');
define('OID_Aruba_wlanAPBssidLoadBalancing',           '.1.3.6.1.4.1.14823.2.2.1.5.2.1.7.1.11');
define('OID_Aruba_wlanAPBssidNumAssociatedStations',   '.1.3.6.1.4.1.14823.2.2.1.5.2.1.7.1.12');
define('OID_Aruba_wlanAPBssidAPMacAddress',            '.1.3.6.1.4.1.14823.2.2.1.5.2.1.7.1.13');
define('OID_Aruba_wlanAPBssidPhyNumber',               '.1.3.6.1.4.1.14823.2.2.1.5.2.1.7.1.14');

// Aruba wlsxWlanStation
define('OID_Aruba_wlanStaPhyAddress',       '.1.3.6.1.4.1.14823.2.2.1.5.2.2.1.1.1');
define('OID_Aruba_wlanStaApBssid',          '.1.3.6.1.4.1.14823.2.2.1.5.2.2.1.1.2');
define('OID_Aruba_wlanStaPhyType',          '.1.3.6.1.4.1.14823.2.2.1.5.2.2.1.1.3');
define('OID_Aruba_wlanStaIsAuthenticated',  '.1.3.6.1.4.1.14823.2.2.1.5.2.2.1.1.4');
define('OID_Aruba_wlanStaIsAssociated',     '.1.3.6.1.4.1.14823.2.2.1.5.2.2.1.1.5');
define('OID_Aruba_wlanStaChannel',          '.1.3.6.1.4.1.14823.2.2.1.5.2.2.1.1.6');
define('OID_Aruba_wlanStaVlanId',           '.1.3.6.1.4.1.14823.2.2.1.5.2.2.1.1.7');
define('OID_Aruba_wlanStaVOIPState',        '.1.3.6.1.4.1.14823.2.2.1.5.2.2.1.1.8');
define('OID_Aruba_wlanStaVOIPProtocol',     '.1.3.6.1.4.1.14823.2.2.1.5.2.2.1.1.9');
define('OID_Aruba_wlanStaTransmitRate',     '.1.3.6.1.4.1.14823.2.2.1.5.2.2.1.1.10');
define('OID_Aruba_wlanStaAssociationID',    '.1.3.6.1.4.1.14823.2.2.1.5.2.2.1.1.11');
define('OID_Aruba_wlanStaAccessPointESSID', '.1.3.6.1.4.1.14823.2.2.1.5.2.2.1.1.12');
define('OID_Aruba_wlanStaPhyNumber',        '.1.3.6.1.4.1.14823.2.2.1.5.2.2.1.1.13');
define('OID_Aruba_wlanStaRSSI',             '.1.3.6.1.4.1.14823.2.2.1.5.2.2.1.1.14');
define('OID_Aruba_wlanStaUpTime',           '.1.3.6.1.4.1.14823.2.2.1.5.2.2.1.1.15');

// Aruba wlsxWlanAPStats
define('OID_Aruba_wlanAPCurrentChannel',           '.1.3.6.1.4.1.14823.2.2.1.5.3.1.1.1.1');
define('OID_Aruba_wlanAPNumClients',               '.1.3.6.1.4.1.14823.2.2.1.5.3.1.1.1.2');
define('OID_Aruba_wlanAPTxPkts',                   '.1.3.6.1.4.1.14823.2.2.1.5.3.1.1.1.3');
define('OID_Aruba_wlanAPTxBytes',                  '.1.3.6.1.4.1.14823.2.2.1.5.3.1.1.1.4');
define('OID_Aruba_wlanAPRxPkts',                   '.1.3.6.1.4.1.14823.2.2.1.5.3.1.1.1.5');
define('OID_Aruba_wlanAPRxBytes',                  '.1.3.6.1.4.1.14823.2.2.1.5.3.1.1.1.6');
define('OID_Aruba_wlanAPTxDeauthentications',      '.1.3.6.1.4.1.14823.2.2.1.5.3.1.1.1.7');
define('OID_Aruba_wlanAPRxDeauthentications',      '.1.3.6.1.4.1.14823.2.2.1.5.3.1.1.1.8');
define('OID_Aruba_wlanAPChannelThroughput',        '.1.3.6.1.4.1.14823.2.2.1.5.3.1.1.1.9');
define('OID_Aruba_wlanAPFrameRetryRate',           '.1.3.6.1.4.1.14823.2.2.1.5.3.1.1.1.10');
define('OID_Aruba_wlanAPFrameLowSpeedRate',        '.1.3.6.1.4.1.14823.2.2.1.5.3.1.1.1.11');
define('OID_Aruba_wlanAPFrameNonUnicastRate',      '.1.3.6.1.4.1.14823.2.2.1.5.3.1.1.1.12');
define('OID_Aruba_wlanAPFrameFragmentationRate',   '.1.3.6.1.4.1.14823.2.2.1.5.3.1.1.1.13');
define('OID_Aruba_wlanAPFrameBandwidthRate',       '.1.3.6.1.4.1.14823.2.2.1.5.3.1.1.1.14');
define('OID_Aruba_wlanAPFrameRetryErrorRate',      '.1.3.6.1.4.1.14823.2.2.1.5.3.1.1.1.15');
define('OID_Aruba_wlanAPChannelErrorRate',         '.1.3.6.1.4.1.14823.2.2.1.5.3.1.1.1.16');
define('OID_Aruba_wlanAPFrameReceiveErrorRate',    '.1.3.6.1.4.1.14823.2.2.1.5.3.1.1.1.17');
define('OID_Aruba_wlanAPRxDataPkts',               '.1.3.6.1.4.1.14823.2.2.1.5.3.1.1.1.18');
define('OID_Aruba_wlanAPRxDataBytes',              '.1.3.6.1.4.1.14823.2.2.1.5.3.1.1.1.19');
define('OID_Aruba_wlanAPTxDataPkts',               '.1.3.6.1.4.1.14823.2.2.1.5.3.1.1.1.20');
define('OID_Aruba_wlanAPTxDataBytes',              '.1.3.6.1.4.1.14823.2.2.1.5.3.1.1.1.21');
define('OID_Aruba_wlanAPRxDataPkts64',             '.1.3.6.1.4.1.14823.2.2.1.5.3.1.1.1.22');
define('OID_Aruba_wlanAPRxDataBytes64',            '.1.3.6.1.4.1.14823.2.2.1.5.3.1.1.1.23');
define('OID_Aruba_wlanAPTxDataPkts64',             '.1.3.6.1.4.1.14823.2.2.1.5.3.1.1.1.24');
define('OID_Aruba_wlanAPTxDataBytes64',            '.1.3.6.1.4.1.14823.2.2.1.5.3.1.1.1.25');

// Aruba wlsxWlanRadio
define('OID_Aruba_wlanAPRadioNumber',                  '.1.3.6.1.4.1.14823.2.2.1.5.2.1.5.1.1');
define('OID_Aruba_wlanAPRadioType',                    '.1.3.6.1.4.1.14823.2.2.1.5.2.1.5.1.2');
define('OID_Aruba_wlanAPRadioChannel',                 '.1.3.6.1.4.1.14823.2.2.1.5.2.1.5.1.3');
define('OID_Aruba_wlanAPRadioTransmitPower',           '.1.3.6.1.4.1.14823.2.2.1.5.2.1.5.1.4');
define('OID_Aruba_wlanAPRadioMode',                    '.1.3.6.1.4.1.14823.2.2.1.5.2.1.5.1.5');
define('OID_Aruba_wlanAPRadioUtilization',             '.1.3.6.1.4.1.14823.2.2.1.5.2.1.5.1.6');
define('OID_Aruba_wlanAPRadioNumAssociatedClients',    '.1.3.6.1.4.1.14823.2.2.1.5.2.1.5.1.7');
define('OID_Aruba_wlanAPRadioNumMonitoredClients',     '.1.3.6.1.4.1.14823.2.2.1.5.2.1.5.1.8');
define('OID_Aruba_wlanAPRadioNumActiveBSSIDs',         '.1.3.6.1.4.1.14823.2.2.1.5.2.1.5.1.9');
define('OID_Aruba_wlanAPRadioNumMonitoredBSSIDs',      '.1.3.6.1.4.1.14823.2.2.1.5.2.1.5.1.10');

