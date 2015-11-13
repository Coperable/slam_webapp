<?php namespace Slam\Model;

use Illuminate\Database\Eloquent\Model;
 
class UserCompetition extends Model {

    protected $table = 'users_competitions';

    protected $fillable = ['user_id', 'competition_id'];

    public function user() {
        return $this->hasOne('Slam\User');
    }

    public function competition() {
        return $this->hasOne('Slam\Model\Competition');
    }


}

