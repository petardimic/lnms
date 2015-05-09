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
        $_ret['ports']['descr']  = ['class'    => 'Port',
                                    'method'   => 'poll_descr',
                                    'initial'  => 'Y',
                                    'default'  => 'Y',
                                    'interval' => '5'];

        $_ret['ports']['status'] = ['class'    => 'Port',
                                    'method'   => 'poll_status',
                                    'initial'  => 'N',
                                    'default'  => 'Y',
                                    'interval' => '5'];

        $_ret['ports']['octets'] = ['class'    => 'Port',
                                    'method'   => 'poll_octets',
                                    'initial'  => 'N',
                                    'default'  => 'Y',
                                    'interval' => '5'];

        return $_ret;
    }
}
