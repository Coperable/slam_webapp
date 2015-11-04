<?php namespace Slam\Model;

use Illuminate\Database\Eloquent\Model;
 
class Competition extends Model {

    protected $fillable = ['title', 'region_id', 'users_limit', 'users_amount', 'description', 'rules', 'event_date', 'location_id', 'active', 'created_by', 'modified_by'];

    protected $dates = ['event_date'];

    public function region() {
        return $this->belongsTo('Slam\Model\Region');
    }

    public function location() {
        return $this->belongsTo('Slam\Model\Location');
    }

    public function users() {
        return $this->belongsToMany('Slam\User', 'users_competitions');
    }

    public function mentions() {
        return $this->hasMany('Slam\Model\Mention');
    }

    public function cups() {
        return $this->hasMany('Slam\Model\Cup');
    }

    public function videos() {
        return $this->hasMany('Slam\Model\Media');
    }

}
