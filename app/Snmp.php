<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Snmp extends Model {

    /**
     * not use 'created_at' and 'updated_at'
     *
     */
    public $timestamps = false;

	//
    protected $fillable = [

    ];

    /*
     * snmps belongs to node
     */
    public function node()
    {
        return $this->belongsTo('\App\Node');
    }
}
