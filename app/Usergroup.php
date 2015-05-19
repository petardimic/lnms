<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Usergroup extends Model {

    protected $fillable = [ 'name' ];

    /**
     * usergroup has many users
     */
    public function users() {
        return $this->hasMany('\App\User');
    }

    /**
     * Generate Usergroup Associate Array
     *
     * @return Array
     */
    static public function all_select() {
        $_ret = array();

        $_ret[''] = '-- usergroup --';

        $usergroups = \App\Usergroup::orderBy('name')->get();

        foreach ($usergroups as $usergroup) {
            $_ret[$usergroup->id] = $usergroup->name;
        }

        return $_ret;
    }

}
