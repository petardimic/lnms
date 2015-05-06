<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Port extends Model {

	//
    protected $fillable = [
        'ifIndex',       'ifDescr',       'ifType',         'ifSpeed',
        'ifPhysAddress', 'ifAdminStatus', 'ifOperStatus',
        'ifName',        'ifAlias',       'node_id',
        'poll_enabled',
    ];

    /*
     * port belongs to node
     */
    public function node()
    {
        return $this->belongsTo('\App\Node');
    }

    /*
     * port has many octets
     */
    public function octets() {
        return $this->hasMany('\App\Octet');
    }
}
