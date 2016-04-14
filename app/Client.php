<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Client extends Model
{
    protected $fillable = ['status', 'group_id'];
    
    public function group(){
        return $this->belongsTo('App\Group');
    }
    
    public function scopeUpdatedThisWeek($query){
        $date = new Carbon('last saturday');
        return $query->where('updated_at', '>', $date->addHours(23));
    }
}
