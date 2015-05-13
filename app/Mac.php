<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Mac extends Model {

    /*
     * mac belongs to node
     */
    public function node()
    {
        return $this->belongsTo('\App\Node');
    }

    /*
     * mac belongs to port
     */
    public function port()
    {
        return $this->belongsTo('\App\Port');
    }

    /*
     * mac belongs to vlan
     */
    public function vlan()
    {
        return $this->belongsTo('\App\Vlan');
    }

}
