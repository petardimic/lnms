<?php namespace App\Lnms\Generic\Snmp;

class Node extends \App\Lnms\Generic\Base\Node {

    /**
     * constructor
     *
     * @return void
     */
    public function __construct() {
    }

    /**
     * list pollers
     *
     * @return Array
     */
    public function pollers() {
        $_ret = [];

        // Node pollers
//        $_ret['nodes']['system']  = ['class'    => 'System',
//                                     'method'   => 'poll_system',
//                                     'initial'  => 'Y',
//                                     'default'  => 'Y',
//                                     'interval' => 'daily'];

        // Port pollers
        $_ret['ports']['ifDescr']  = ['class'    => 'Port',
                                      'method'   => 'poll_ifDescr',
                                      'initial'  => 'Y',
                                      'default'  => 'Y',
                                      'interval' => 'daily'];

//        $_ret['ports']['ifType'] =   ['class'    => 'Port',
//                                      'method'   => 'poll_ifType',
//                                      'initial'  => 'Y',
//                                      'default'  => 'Y',
//                                      'interval' => 'daily'];
//
//        $_ret['ports']['ifPhysAddress'] = ['class'    => 'Port',
//                                           'method'   => 'poll_ifPhysAddress',
//                                           'initial'  => 'Y',
//                                           'default'  => 'Y',
//                                           'interval' => 'daily'];
//
//        $_ret['ports']['ifSpeed'] =  ['class'    => 'Port',
//                                      'method'   => 'poll_ifSpeed',
//                                      'initial'  => 'Y',
//                                      'default'  => 'Y',
//                                      'interval' => 'daily'];
//
//        $_ret['ports']['ifStatus'] = ['class'    => 'Port',
//                                      'method'   => 'poll_ifStatus',
//                                      'initial'  => 'Y',
//                                      'default'  => 'Y',
//                                      'interval' => 'daily'];
//
//        $_ret['ports']['ifName'] =   ['class'    => 'Port',
//                                      'method'   => 'poll_ifName',
//                                      'initial'  => 'Y',
//                                      'default'  => 'Y',
//                                      'interval' => 'daily'];
//
//        $_ret['ports']['ifAlias'] =  ['class'    => 'Port',
//                                      'method'   => 'poll_ifAlias',
//                                      'initial'  => 'Y',
//                                      'default'  => 'Y',
//                                      'interval' => 'daily'];
//
//        $_ret['ports']['ifOctets'] = ['class'    => 'Port',
//                                      'method'   => 'poll_ifOctets',
//                                      'initial'  => 'N',
//                                      'default'  => 'Y',
//                                      'interval' => '5'];
//
//        $_ret['ips']['ipAddress']  = ['class'    => 'Ip',
//                                      'method'   => 'poll_ipAddress',
//                                      'initial'  => 'Y',
//                                      'default'  => 'Y',
//                                      'interval' => 'daily'];
//
//        $_ret['arps']['arpAddress'] = ['class'    => 'Arp',
//                                       'method'   => 'poll_arpAddress',
//                                       'initial'  => 'Y',
//                                       'default'  => 'Y',
//                                       'interval' => 'daily'];
//
//        $_ret['routes']['routeDest'] = ['class'    => 'Route',
//                                       'method'   => 'poll_routeDest',
//                                       'initial'  => 'Y',
//                                       'default'  => 'Y',
//                                       'interval' => 'daily'];

        return $_ret;
    }

}
