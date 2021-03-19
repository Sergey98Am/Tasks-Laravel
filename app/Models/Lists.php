<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use ShiftOneLabs\LaravelCascadeDeletes\CascadesDeletes;

class Lists extends Model
{
    use SoftDeletes, CascadesDeletes;

    protected $cascadeDeletes = ['cards'];

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
