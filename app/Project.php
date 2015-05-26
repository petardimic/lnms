<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model {

	//
    protected $fillable = [ 'name', 'logo_file_name', 'logo_file_type' ];

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
    static public function all_select($show_all='') {
        $_ret = array();

        $_ret[''] = '-- project --';

        if ($show_all == 'all') {
            $_ret[1] = '** ALL **';
        }

        $projects = \App\Project::where('id', '>', 1)
                                ->orderBy('name')
                                ->get();

        foreach ($projects as $project) {
            $_ret[$project->id] = $project->name;
        }

        return $_ret;
    }

//    /**
//     * project belongs to many roles
//     */
//    public function roles() {
//        return $this->belongsToMany('\App\Role');
//    }
}
