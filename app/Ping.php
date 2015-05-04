<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Ping extends Model {

	//
    protected $fillable = [

    ];

    /*
     * pings belongs to node
     */
    public function node()
    {
        return $this->belongsTo('\App\Node');
    }
}
