<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model {

	//
    protected $fillable = [ 'user_id',
                            'usergroup_id',
                            'location_id',
                            'project_id'
                          ];

//    /**
//     * roles belongs to many users
//     */
//    public function users() {
//        return $this->belongsToMany('\App\User', 'roles');
//    }
//
//    /**
//     * roles belongs to many usergroups
//     */
//    public function usergroups() {
//        return $this->belongsToMany('\App\Usergroup', 'roles');
//    }
//
//    /**
//     * roles belongs to many locations
//     */
//    public function locations() {
//        return $this->belongsToMany('\App\Location', 'roles');
//    }
//
//    /**
//     * roles belongs to many projects
//     */
//    public function projects() {
//        return $this->belongsToMany('\App\Project', 'roles');
//    }
}
