<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model {

	//
    protected $fillable = [ 'name' ];

    /**
     * project has many nodes
     */
    public function nodes() {
        return $this->hasMany('\App\Node');
    }

}
