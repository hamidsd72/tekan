<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'comments';

    protected $fillable = [
        "type",
        "item_id",
        "user_id",
        "text",
        "status",
    ];

    public function user() {
        return $this->belongsTo('App\User','user_id')->first();
    }
    
}
