<?php namespace Slam\Model;

use Illuminate\Database\Eloquent\Model;
 
class UserRegion extends Model {

    protected $table = 'users_regions';

    public function user() {
        return $this->hasOne('Slam\User');
    }

    public function region() {
        return $this->hasOne('Slam\Model\Region');
    }


}
