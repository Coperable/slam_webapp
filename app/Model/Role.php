<?php namespace Slam\Model;

use Illuminate\Database\Eloquent\Model;
 
class Role extends Model {

    protected $hidden = ['created_at', 'updated_at'];

    public function users() {
        return $this->belongsToMany('User', 'users_roles');
    }
}
