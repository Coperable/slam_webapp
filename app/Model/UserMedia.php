<?php namespace Slam\Model;

use Illuminate\Database\Eloquent\Model;
 
class UserMedia extends Model {

    protected $table = 'users_medias';

    protected $fillable = ['user_id', 'media_id'];

    public function user() {
        return $this->hasOne('Slam\User');
    }

    public function media() {
        return $this->hasOne('Slam\Model\Media');
    }


}


