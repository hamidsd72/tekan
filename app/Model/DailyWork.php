<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class DailyWork extends Model {

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $fillable = [
        "user_id",
    ];

    public function user() {
        return $this->belongsTo('App\User','user_id');
    }

}

