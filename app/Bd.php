<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Bd extends Model {

    /**
     * bds belongs to node
     */
    public function node()
    {
        return $this->belongsTo('\App\Node');
    }

    /**
     * bds belongs to bssid
     */
    public function bssid()
    {
        return $this->belongsTo('\App\Bssid');
    }

}
