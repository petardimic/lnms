<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Polling extends Model {

    /*
     * polling has many pds
     */
    public function pds() {
        return $this->hasMany('\App\Pd');
    }

}
