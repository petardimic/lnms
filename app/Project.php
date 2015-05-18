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

    /**
     * Generate Project Associate Array
     *
     * @return Array
     */
    static public function all_select() {
        $_ret = array();

        $_ret[''] = 'unknown';

        $projects = \App\Project::orderBy('name')->get();

        foreach ($projects as $project) {
            $_ret[$project->id] = $project->name;
        }

        return $_ret;
    }

}
