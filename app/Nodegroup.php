<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Nodegroup extends Model {

	//
    protected $fillable = [ 'name' ];

    /**
     * nodegroup has many nodes
     */
    public function nodes() {
        return $this->hasMany('\App\Node');
    }

    /**
     * Generate Nodegroup Associate Array
     *
     * @return Array
     */
    static public function all_select() {
        $_ret = array();

        $_ret[''] = 'unknown';

        $nodegroups = \App\Nodegroup::orderBy('name')->get();

        foreach ($nodegroups as $nodegroup) {
            $_ret[$nodegroup->id] = $nodegroup->name;
        }

        return $_ret;
    }

}
