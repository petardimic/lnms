<?php namespace App\Lnms\Netsnmp\Linux;

/*
 */

class Node {

    public function pollers() {
        $_ret = [];

        // Node pollers
        $_ret['nodes']['load5'] = ['default' => 'Y', 'interval' => '5'];

        // Port pollers
        $_ret['ports']['status'] = ['default' => 'Y', 'interval' => '5'];
        $_ret['ports']['octets'] = ['default' => 'Y', 'interval' => '5'];
        $_ret['ports']['octets'] = ['default' => 'Y', 'interval' => '5'];

        return $_ret;
    }
}
