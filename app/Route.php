<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Route extends Model {

    /*
     * route belongs to node
     */
    public function node()
    {
        return $this->belongsTo('\App\Node');
    }

    /*
     * route belongs to port
     */
    public function port()
    {
        return $this->belongsTo('\App\Port');
    }

}
