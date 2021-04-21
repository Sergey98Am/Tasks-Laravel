<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialAccount extends Model
{
    protected $fillable = ['provider', 'provider_user_id', 'user_id'];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
