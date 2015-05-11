<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Pd extends Model {

    /*
     * pd belongs to pollings
     */
    public function polling()
    {
        return $this->belongsTo('\App\Polling');
    }

}
