<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model {

	//
    protected $fillable = [ 'name' ];

    /**
     * permission belongs to many usergroups
     */
    public function usergroups() {
        return $this->belongsToMany('\App\Usergroup');
    }
}
