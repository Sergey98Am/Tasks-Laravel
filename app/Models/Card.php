<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $fillable = [
        'title',
        'priority',
        'lists_id',
    ];

    public function list()
    {
        return $this->belongsTo('App\Models\Card');
    }

    public function members()
    {
        return $this->belongsToMany('App\Models\User');
    }

    public function comments()
    {
        return $this->hasMany('App\Models\Comment')->where('parent_id', null);
    }
}
