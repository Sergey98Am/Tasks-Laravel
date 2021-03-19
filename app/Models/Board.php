<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use ShiftOneLabs\LaravelCascadeDeletes\CascadesDeletes;

class Board extends Model
{
    use SoftDeletes, CascadesDeletes;

    protected $cascadeDeletes = ['lists'];

    protected $fillable = [
        'title',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function lists()
    {
        return $this->hasMany('App\Models\Lists');
    }
}
