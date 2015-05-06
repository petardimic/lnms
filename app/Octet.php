<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Octet extends Model {

    /**
     * not use 'created_at' and 'updated_at'
     *
     */
    public $timestamps = false;

	//
    protected $fillable = [ 'port_id', 'timestamp', 'input', 'output', ];

    /*
     * Octet belongs to Port
     */
    public function port()
    {
        return $this->belongsTo('\App\Port');
    }


}
