<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class Ask extends Model
{
    protected $fillable = ['from_user', 'to_user', 'created_at'];
    
    public function user_from()
    {
        return $this->belongsTo('App\User','from_user');
    }
    
    public function user_to()
    {
        return $this->belongsTo('App\User','to_user');
    }
}