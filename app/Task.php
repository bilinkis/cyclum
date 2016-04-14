<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['status', 'text', 'user_id', 'validated', 'subteam_id'];
    
    public function user(){
        return $this->belongsTo('App\User');
    }
    
    public function workers(){
        return $this->belongsToMany('App\User', 'tasks_workers', 'task_id', 'worker_id');
    }
    
    public function groups(){
        return $this->hasMany('App\Group');
    }
    
    public function scopeWhereStatus($query, $status){
        $query->where('status', '=', $status);
    }
    
    public function scopeAccepted($query){
        return $query->where('validated', '=', 'accepted');
    }
}
