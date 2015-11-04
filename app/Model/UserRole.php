<?php namespace Slam\Model;

use Illuminate\Database\Eloquent\Model;
 
class UserRole extends Model {

    protected $table = 'users_roles';

    public function user() {
        return $this->hasOne('Slam\User');
    }

    public function role() {
        return $this->hasOne('Slam\Model\Role');
    }


}

