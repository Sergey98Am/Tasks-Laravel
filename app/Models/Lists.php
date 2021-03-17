<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lists extends Model
{
    use SoftDeletes;

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
