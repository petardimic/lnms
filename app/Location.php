<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Location extends Model {

	//
    protected $fillable = [ 'name' ];

    /**
     * location has many nodes
     */
    public function nodes() {
        return $this->hasMany('\App\Node');
    }


    /**
     * Generate Location Associate Array
     *
     * @return Array
     */
    static public function all_select() {
        $_ret = array();

        $_ret[''] = 'unknown';

        $locations = \App\Location::all();

        foreach ($locations as $location) {
            $_ret[$location->id] = $location->name;
        }

        return $_ret;
    }

}
