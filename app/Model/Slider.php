<?php namespace Slam\Model;

use Illuminate\Database\Eloquent\Model;
 
class Slider extends Model {

    protected $hidden = ['created_at', 'updated_at'];

    public function media() {
        return $this->belongsTo('Slam\Model\Media');
    }

}
