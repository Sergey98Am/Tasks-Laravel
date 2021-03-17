<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Card extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'lists_id',
    ];

    public function list()
    {
        return $this->belongsTo('App\Models\Card');
    }
}
