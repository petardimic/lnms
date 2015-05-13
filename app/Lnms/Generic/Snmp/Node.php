<?php namespace App\Lnms\Generic\Snmp;

class Node extends \App\Lnms\Generic\Base\Node {

    /**
     * list pollers
     *
     * @return Array
     */
    public function pollers() {
        $_ret = [];

        // Port pollers
        $_ret['ports']['ifDescr']  = ['class'    => 'Port',
                                      'method'   => 'poll_ifDescr',
                                      'initial'  => 'Y',
                                      'default'  => 'Y',
                                      'interval' => 'daily'];

        $_ret['ports']['ifType'] =   ['class'    => 'Port',
                                      'method'   => 'poll_ifType',
                                      'initial'  => 'Y',
                                      'default'  => 'Y',
                                      'interval' => 'daily'];

        $_ret['ports']['ifSpeed'] =  ['class'    => 'Port',
                                      'method'   => 'poll_ifSpeed',
                                      'initial'  => 'Y',
                                      'default'  => 'Y',
                                      'interval' => 'daily'];

        $_ret['ports']['ifStatus'] = ['class'    => 'Port',
                                      'method'   => 'poll_ifStatus',
                                      'initial'  => 'N',
                                      'default'  => 'Y',
                                      'interval' => '5'];

        $_ret['ports']['ifOctets'] = ['class'    => 'Port',
                                      'method'   => 'poll_ifOctets',
                                      'initial'  => 'N',
                                      'default'  => 'Y',
                                      'interval' => '5'];

        $_ret['ips']['ipAddress']  = ['class'    => 'Ip',
                                      'method'   => 'poll_ipAddress',
                                      'initial'  => 'Y',
                                      'default'  => 'Y',
                                      'interval' => 'daily'];

        $_ret['arps']['arpAddress'] = ['class'    => 'Arp',
                                       'method'   => 'poll_arpAddress',
                                       'initial'  => 'Y',
                                       'default'  => 'Y',
                                       'interval' => 'daily'];

        return $_ret;
    }
}
