<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password', 'teamName', 'mail_validated', 'rank', 'leader_id', 'subteam_id'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];
    
    public function workers(){
        return $this->hasMany('App\User', 'leader_id');
    }
    
    public function leader(){
        return $this->belongsTo('App\User', 'leader_id');
    }
    
    public function tasks(){
        if($this->attributes['rank'] == 'leader')
            return $this->hasMany('App\Task');
        else if($this->attributes['rank'] == 'worker')
            return $this->belongsToMany('App\Task', 'tasks_workers', 'worker_id', 'task_id');
    }
    
    public function isALeader() {
        return ($this->attributes['rank'] == 'leader');
    }
    
    public function isAWorker() {
        return ($this->attributes['rank'] == 'worker');
    }
    
    public function stats() {
        return $this->hasMany('App\Stat');
    }
    
    public function groups(){
        return $this->hasMany('App\Group', 'project_id');
    }
    
    public function scopeLeaders($query){
        return $query->where('rank', '=', 'leader');
    }
    
    public function teams() {
        return $this->hasMany('App\Subteam', 'user_id');
    }
}