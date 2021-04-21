<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
        'title',
    ];

    public function users()
    {
        return $this->hasMany('App\Models\User');
    }

    public function permissions(){
        return $this->belongsToMany('App\Models\Permission');
    }
}
