<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Target extends Model {
    protected $table = 'targets';

    protected $fillable = [
        "user_id",
        "level",
        "personal",
        "network",
        "burning",
        "other",
        "date",
    ];

    public function user() {
        return $this->belongsTo('App\User','user_id');
    }

}
