<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use ShiftOneLabs\LaravelCascadeDeletes\CascadesDeletes;

class SocialAccount extends Model
{
    use SoftDeletes, CascadesDeletes;

    protected $fillable = ['provider', 'provider_user_id', 'user_id'];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
