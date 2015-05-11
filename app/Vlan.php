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

}
