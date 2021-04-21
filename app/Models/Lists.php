<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lists extends Model
{
    protected $fillable = [
        'title',
        'board_id',
    ];

    public function board()
    {
        return $this->belongsTo('App\Models\Board');
    }

    public function cards()
    {
        return $this->hasMany('App\Models\Card');
    }
}
