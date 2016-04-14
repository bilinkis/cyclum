<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = ['url', 'views', 'averageTime', 'group_id'];
    
    public function group(){
        return $this->belongsTo('App\Group');
    }
    
    public function scopeWhereUrl($query, $url){
        $query->where('url', '=', $url);
    }
}
