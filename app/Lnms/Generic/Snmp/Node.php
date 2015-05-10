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
                                      'initial'  => 'N',
                                      'default'  => 'Y',
                                      'interval' => '5'];

        $_ret['ports']['ifType'] =   ['class'    => 'Port',
                                      'method'   => 'poll_ifType',
                                      'initial'  => 'Y',
                                      'default'  => 'Y',
                                      'interval' => '5'];

        $_ret['ports']['ifStatus'] = ['class'    => 'Port',
                                      'method'   => 'poll_ifStatus',
                                      'initial'  => 'N',
                                      'default'  => 'Y',
                                      'interval' => '5'];

        $_ret['ports']['ifOctets'] = ['class'    => 'Port',
                                      'method'   => 'poll_ifOctets',
                                      'initial'  => 'Y',
                                      'default'  => 'Y',
                                      'interval' => '5'];

        return $_ret;
    }
}
