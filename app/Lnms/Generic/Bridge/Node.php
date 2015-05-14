<?php namespace App\Lnms\Generic\Bridge;

class Node extends \App\Lnms\Generic\Snmp\Node {

    /**
     * override parentlist pollers
     *
     * @return Array
     */
    public function pollers() {

        $_ret = parent::pollers();

//        $_ret['ports']['portIndex'] = ['class'    => 'Port',
//                                       'method'   => 'poll_portIndex',
//                                       'initial'  => 'Y',
//                                       'default'  => 'Y',
//                                       'interval' => 'daily'];
//
//        $_ret['vlans']['vlanName'] = ['class'    => 'Vlan',
//                                      'method'   => 'poll_vlanName',
//                                      'initial'  => 'Y',
//                                      'default'  => 'Y',
//                                      'interval' => 'daily'];
//
//        $_ret['vlans']['vlanMember'] = ['class'    => 'Vlan',
//                                        'method'   => 'poll_vlanMember',
//                                        'initial'  => 'Y',
//                                        'default'  => 'Y',
//                                        'interval' => 'daily'];
//        $_ret['macs']['macAddress'] =  ['class'    => 'Mac',
//                                        'method'   => 'poll_macAddress',
//                                        'initial'  => 'Y',
//                                        'default'  => 'Y',
//                                        'interval' => 'daily'];
        return $_ret;
    }
}
