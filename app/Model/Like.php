<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Like extends Model {
    protected $table = 'likes';

    protected $fillable = [
        "type",
        "item_id",
        "user_id",
        "status",
        "star",
    ];

    public function user() {
        return $this->belongsTo('App\User','user_id')->first();
    }
}
