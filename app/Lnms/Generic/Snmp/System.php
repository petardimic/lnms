<?php namespace App\Lnms\Generic\Snmp;

class System {

    /**
     * \App\Node
     */
    protected $node;

    /**
     * constructor
     *
     * @return void
     */
    public function __construct(\App\Node $node) {
        $this->node = $node;
    }

    /**
     * poller system
     *
     * @return Array
     */
    public function poll_system() {

        // return
        $_ret = [];

        $snmp = new \App\Lnms\Snmp($this->node->ip_address, $this->node->snmp_comm_ro);

        // get system
        $get_system = $snmp->get([OID_sysDescr,
                                  OID_sysObjectID,
                                  OID_sysContact,
                                  OID_sysName,
                                  OID_sysLocation,
                                 ]);

        if (count($get_system) == 0) {
            // not found system
            return $_ret;
        }

        $sysDescr    = $get_system[OID_sysDescr];
        $sysObjectID = $get_system[OID_sysObjectID];
        $sysContact  = $get_system[OID_sysContact];
        $sysName     = $get_system[OID_sysName];
        $sysLocation = $get_system[OID_sysLocation];

        // update data
        $_ret[] =  [ 'table'  => 'nodes',
                     'action' => 'update',
                     'key'    => [ 'id' => $this->node->id, ],
                     'data'   => [ 'sysDescr'    => $sysDescr,
                                   'sysObjectID' => $sysObjectID,
                                   'sysContact'  => $sysContact,
                                   'sysName'     => $sysName,
                                   'sysLocation' => $sysLocation,
                                   ],
                    ];

        return $_ret;
    }
}
