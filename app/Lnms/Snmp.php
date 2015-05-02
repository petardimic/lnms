<?php namespace App\Lnms;

// mibs system
// -----------
define('OID_sysDescr',      '.1.3.6.1.2.1.1.1.0');
define('OID_sysObjectID',   '.1.3.6.1.2.1.1.2.0');
define('OID_sysUpTime',     '.1.3.6.1.2.1.1.3.0');
define('OID_sysContact',    '.1.3.6.1.2.1.1.4.0');
define('OID_sysName',       '.1.3.6.1.2.1.1.5.0');
define('OID_sysLocation',   '.1.3.6.1.2.1.1.6.0');

// mibs interfaces
// ---------------
define('OID_ifDescr',       '.1.3.6.1.2.1.2.2.1.2');
define('OID_ifType',        '.1.3.6.1.2.1.2.2.1.3');
define('OID_ifMtu',         '.1.3.6.1.2.1.2.2.1.4');
define('OID_ifSpeed',       '.1.3.6.1.2.1.2.2.1.5');
define('OID_ifPhysAddress', '.1.3.6.1.2.1.2.2.1.6');
define('OID_ifAdminStatus', '.1.3.6.1.2.1.2.2.1.7');
define('OID_ifOperStatus',  '.1.3.6.1.2.1.2.2.1.8');

define('OID_ifName',        '.1.3.6.1.2.1.31.1.1.1.1');
define('OID_ifHighSpeed',   '.1.3.6.1.2.1.31.1.1.1.15');
define('OID_ifAlias',       '.1.3.6.1.2.1.31.1.1.1.18');

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

        // SNMP Get Command
        $this->snmpget = 'snmpget -O ' . $this->output_options
                           . ' -v ' . $snmp_version
                           . ' -c ' . $snmp_comm_ro . ' ' . $ip_address ;

        // SNMP Walk Command
        $this->snmpwalk = 'snmpwalk -O ' . $this->output_options
                           . ' -v ' . $snmp_version
                           . ' -c ' . $snmp_comm_ro . ' ' . $ip_address ;

    }

    /*
     * run snmpget
     */
    public function get($oids) {

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
