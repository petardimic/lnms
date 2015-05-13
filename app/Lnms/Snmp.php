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

        // SNMP Get Command
        $this->snmpget = 'snmpget -O ' . $this->output_options
                           . ' ' . $this->options
                           . ' -v ' . $this->snmp_version
                           . ' -c ' . $this->snmp_comm_ro . ' ' . $this->ip_address ;

        if ( is_array($oids) ) {
            $oids = implode(' ', $oids);
        }

        // clear last error before snmpget again
        $this->error = '';
        exec("$this->snmpget $oids 2>&1", $exec_out1, $exec_ret1);
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

