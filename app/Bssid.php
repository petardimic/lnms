<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Bssid extends Model {

    /*
     * bssid belongs to node
     */
    public function node()
    {
        return $this->belongsTo('\App\Node');
    }

    /*
     * bssid belongs to port
     */
    public function port()
    {
        return $this->belongsTo('\App\Port');
    }

}
