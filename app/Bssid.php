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

    /**
     *
     */
    public function getDspBssidSpecAttribute() {
        $bssidSpecArray = [
            '1' => '802.11a',
            '2' => '802.11b',
            '3' => '802.11g',
        ];

        if ( isset($bssidSpecArray[$this->bssidSpec]) ) {
            return $bssidSpecArray[$this->bssidSpec];
        } else {
            return $this->bssidSpec;
        }
    }

}
