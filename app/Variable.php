<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Variable extends Model
{
    protected $fillable = ['name', 'value', 'group_id'];
    
    public function group(){
        return $this->belongsTo('App\Group');
    }
}
