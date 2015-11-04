<?php namespace Slam\Model;

use Illuminate\Database\Eloquent\Model;
 
class Media extends Model {

    protected $fillable = [ 'name', 'ext', 'type', 'user_id'];

    protected $table = 'medias';

    public function user() {
        return $this->belongsTo('Busca\User');
    }

}
