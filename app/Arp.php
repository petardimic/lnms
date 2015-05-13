<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Arp extends Model {

    /*
     * ip belongs to node
     */
    public function node()
    {
        return $this->belongsTo('\App\Node');
    }

    /*
     * ip belongs to port
     */
    public function port()
    {
        return $this->belongsTo('\App\Port');
    }
}
