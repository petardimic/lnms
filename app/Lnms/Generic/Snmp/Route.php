<?php namespace App\Lnms\Generic\Snmp;

class Route {

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
     * poller routeDest
     *
     * @return Array
     */
    public function poll_routeDest() {

        // return
        $_ret = [];

        $snmp = new \App\Lnms\Snmp($this->node->ip_address, $this->node->snmp_comm_ro);


        // walk ipRouteIfIndex
        $walk_ipRouteIfIndex = $snmp->walk(OID_ipRouteIfIndex);

        if (count($walk_ipRouteIfIndex) == 0) {
            // not found route
            return $_ret;
        }

        //
        $walk_ipRouteNextHop = $snmp->walk(OID_ipRouteNextHop);
        $walk_ipRouteType    = $snmp->walk(OID_ipRouteType);
        $walk_ipRouteProto   = $snmp->walk(OID_ipRouteProto);
        $walk_ipRouteMask    = $snmp->walk(OID_ipRouteMask);

        // oid.routeDest = ifIndex
        foreach ($walk_ipRouteIfIndex as $key1 => $ifIndex) {

            $routeDest      = str_replace(OID_ipRouteIfIndex . '.', '', $key1);
            $routeNextHop   = $walk_ipRouteNextHop[OID_ipRouteNextHop . '.' . $routeDest];
            $routeType      = $walk_ipRouteType[OID_ipRouteType . '.' . $routeDest];
            $routeProto     = $walk_ipRouteProto[OID_ipRouteProto . '.' . $routeDest];
            $netmask        = explode('.', $walk_ipRouteMask[OID_ipRouteMask . '.' . $routeDest]);

            $routeMasks = substr_count(decbin($netmask[0]), 1)
                                + substr_count(decbin($netmask[1]), 1)
                                + substr_count(decbin($netmask[2]), 1)
                                + substr_count(decbin($netmask[3]), 1);

            $port = \App\Port::where('node_id', $this->node->id)
                             ->where('ifIndex', $ifIndex)
                             ->first();

            if ($port) {

                // found port
                $_ret[] =  [ 'table'  => 'routes',
                             'action' => 'sync',
                             'key'    => [ 'node_id'    => $this->node->id,
                                           'port_id'    => $port->id,
                                           'routeDest'  => $routeDest,
                                           'routeMasks' => $routeMasks,
                                         ],
    
                             'data'   => [ 'routeNextHop' => $routeNextHop,
                                           'routeType'    => $routeType,
                                           'routeProto'   => $routeProto,
                                         ],
                            ];
            }
        }

        return $_ret;
    }

}
