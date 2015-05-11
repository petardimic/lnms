<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Vlan extends Model {

    /*
     * vlan belongs to node
     */
    public function node()
    {
        return $this->belongsTo('\App\Node');
    }

    /*
     * vlan belongs to many ports
     */
    public function ports()
    {
        return $this->belongsToMany('\App\Port');
    }

}
