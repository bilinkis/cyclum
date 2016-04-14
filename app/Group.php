<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = ['task_id', 'project_id'];
    
    public function task(){
        return $this->belongsTo('App\Task');
    }
    
    public function project(){
        return $this->belongsTo('App\User');
    }
    
    public function pages(){
        return $this->hasMany('App\Page');
    }
    
    public function variables(){
        return $this->hasMany('App\Variable');
    }
    
    public function clients(){
        return $this->hasMany('App\Client');
    }
}
