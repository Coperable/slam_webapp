<?php namespace Slam\Model;

use Illuminate\Database\Eloquent\Model;
 
class Region extends Model {

    protected $hidden = ['created_at', 'updated_at'];

    public function competitions() {
        return $this->hasMany('Slam\Model\Competition');
    }

    public function users() {
        return $this->belongsToMany('Slam\User', 'users_regions');
    }


}


