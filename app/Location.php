<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Location extends Model {

	//
    protected $fillable = [ 'name', 'parent_id' ];

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
    static public function all_select($show_all='') {
        $_ret = array();

        $_ret['']  = '-- location --';

        if ($show_all == 'all') {
            $_ret[1] = '** ALL **';
        }

        $locations = \App\Location::where('id', '>', 1)
                                  ->get();

        foreach ($locations as $location) {
            $_ret[$location->id] = $location->name;
        }

        return $_ret;
    }

//    /**
//     * location belongs to many roles
//     */
//    public function roles() {
//        return $this->belongsToMany('\App\Role');
//    }
}
