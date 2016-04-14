<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subteam extends Model
{
    protected $fillable = ['name', 'user_id'];
    
    public function leader() {
        return $this->belongsTo('App\User', 'user_id');
    }
    
    public function tasks() {
        return $this->hasMany('App\Task', 'subteam_id');
    }
    
    public function workers() { // Mejor conocida como JUANCHIIIIIIIIII
        return $this->hasMany('App\User', 'subteam_id');
    }
}
