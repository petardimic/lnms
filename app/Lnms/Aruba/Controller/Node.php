<?php namespace App\Lnms\Aruba\Controller;

class Node extends \App\Lnms\Generic\Snmp\Node {

    /**
     * walk Access Point
     *
     * @return Array
     */
    public function pollers() {

//        $_ret = parent::pollers();

        $_ret = [];

//        $_ret['nodes']['apAddress'] = ['class'    => 'Ap',
//                                       'method'   => 'poll_apAddress',
//                                       'initial'  => 'Y',
//                                       'default'  => 'Y',
//                                       'interval' => 'daily'];

        $_ret['bssids']['apBssid'] = ['class'    => 'Ap',
                                      'method'   => 'poll_apBssid',
                                      'initial'  => 'Y',
                                      'default'  => 'Y',
                                      'interval' => 'daily'];
        return $_ret;
    }
}
