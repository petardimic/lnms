<?php namespace App\Lnms\Hp\Bridge;

class Vlan extends \App\Lnms\Generic\Bridge\Vlan {

    /**
     * Override the parent
     *
     * @return Array
     */
    public function poll_vlanName($vlanIndex='') {

        $_ret_from_parent = parent::poll_vlanName();

        if ( count($_ret_from_parent) > 0 ) {
            return $_ret_from_parent;
        }

        // TODO: cannot find another way to walk vlan on HP Switch
        // so now, return only VLAN 1
        
        $_ret = [];

        $_ret[] =  [ 'table'  => 'vlans',
                     'action' => 'sync',
                     'key'    => [ 'node_id'   => $this->node->id,
                                   'vlanIndex' => '1' ],

                     'data'   => [ 'vlanName' => 'VLAN 1' ],
                            ];
        return $_ret;
    }

    /**
     * Override the parent
     *
     * @return Array
     */
    public function poll_vlanMember($vlanIndex='') {

        $_ret_from_parent = parent::poll_vlanMember();

        if ( count($_ret_from_parent) > 0 ) {
            return $_ret_from_parent;
        }

        // TODO: cannot find another way to walk vlanMember on HP Switch
        // so now, assume every ports are assigned in VLAN 1

        // return
        $_ret = [];

        $mVlan = \App\Vlan::where('node_id', $this->node->id)
                          ->where('vlanIndex', 1)
                          ->first();
        $mVlans[] = $mVlan->id;

        //
        $mPorts = \App\Port::where('node_id', $this->node->id)
                           ->where('portIndex', '>', 0)
                           ->get();
        foreach ($mPorts as $mPort) {
            $mPort->vlans()->sync($mVlans);
        }

        return $_ret;
    }
}
