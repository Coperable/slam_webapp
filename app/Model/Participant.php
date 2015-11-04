<?php namespace Slam\Model;

use Illuminate\Database\Eloquent\Model;
 
class Participant extends Model {

    protected $table = 'users';
    
    public function competitions() {
        return $this->belongsToMany('Slam\Model\Competition', 'regions_competitions');
    }

}



