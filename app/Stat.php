<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stat extends Model
{
    protected $fillable = [
        'name', 'variation', 'value', 'type', 'user_id'
    ];
    
    public function user(){
        return $this->belongsTo('App\User');
    }
    
    public function scopeStates($query){
        return $query->where('type', '=', 'state');
    }
    
    public function scopeVariables($query){
        return $query->where('type', '=', 'variable');
    }
    
    public function scopeTimes($query){
        return $query->where('type', '=', 'time');
    }
}
