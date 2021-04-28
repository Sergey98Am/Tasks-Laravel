<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    protected $fillable = [
        'title',
    ];

    public function users()
    {
        return $this->belongsToMany('App\Models\User');
    }

    public function lists()
    {
        return $this->hasMany('App\Models\Lists');
    }
}
