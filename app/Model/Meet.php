<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Meet extends Model {

    use SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $fillable = [
        "user_id",
        "title",
        "slug",
        "reply",
        "addDays",
        "total",
        "date",
        "ready_date",
    ];

    public function descriptions() {
        return $this->hasMany('App\Model\MeetDescription','meet_id');
    }

    public function reports() {
        return $this->hasMany('App\Model\MeetReport','meet_id')->orderByDesc('id');
    }

}

