<?php namespace App\Lnms\Generic\Bridge;

class Node extends \App\Lnms\Generic\Snmp\Node {

    /**
     * override parentlist pollers
     *
     * @return Array
     */
    public function pollers() {

        $_ret = parent::pollers();

        $_ret['vlans']['vlanName'] = ['class'    => 'Vlan',
                                      'method'   => 'poll_vlanName',
                                      'initial'  => 'Y',
                                      'default'  => 'Y',
                                      'interval' => 'daily'];

        return $_ret;
    }
}
