<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Node extends Model {

	//
    protected $fillable = [ 'name', 'ip_address',
                            'snmp_version', 'snmp_comm_ro',
                            'poll_enabled' ];

    /*
     * node has many ports
     */
    public function ports() {
        return $this->hasMany('\App\Port');
    }

    /*
     * node has many pings
     */
    public function pings() {
        return $this->hasMany('\App\Ping');
    }

    /*
     * node has many snmps
     */
    public function snmps() {
        return $this->hasMany('\App\Snmp');
    }

}
