<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Poller extends Model {

	//
    protected $fillable = [ 'table_name', 'name', 'status', 'interval' ];

}
