<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Node extends Model {

	//
    protected $fillable = [
        'name',
        'ip_address',
        'snmp_comm_ro',
    ];

    /*
     * node has many ports
     */
    public function ports() {
        return $this->hasMany('\App\Port');
    }

}
