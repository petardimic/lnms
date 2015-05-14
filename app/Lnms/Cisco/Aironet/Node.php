<?php namespace App\Lnms\Cisco\Aironet;

class Node extends \App\Lnms\Generic\Snmp\Node {

    /**
     * override parentlist pollers
     *
     * @return Array
     */
    public function pollers() {

        $_ret = parent::pollers();

        $_ret['bssids']['ssidName'] = ['class'    => 'Bssid',
                                       'method'   => 'poll_ssidName',
                                       'initial'  => 'Y',
                                       'default'  => 'Y',
                                       'interval' => 'daily'];
        return $_ret;
    }
}
