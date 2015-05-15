<?php namespace App\Lnms\Cisco\Aironet;

class Node extends \App\Lnms\Generic\Snmp\Node {

    /**
     * override parentlist pollers
     *
     * @return Array
     */
    public function pollers() {

        $_ret = parent::pollers();

        $_ret['bssids']['bssidName'] = ['class'    => 'Bssid',
                                        'method'   => 'poll_bssidName',
                                        'initial'  => 'Y',
                                        'default'  => 'Y',
                                        'interval' => 'daily'];

        $_ret['bds']['clientMacAddress'] = ['class'    => 'Bssid',
                                            'method'   => 'poll_clientMacAddress',
                                            'initial'  => 'Y',
                                            'default'  => 'Y',
                                            'interval' => 'daily'];
        return $_ret;
    }
}
