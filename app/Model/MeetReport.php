<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MeetReport extends Model {

    use SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $fillable = [
        "user_id",
        "meet_id",
        "text",
    ];

    public function user() {
        return $this->belongsTo('App\User','user_id');
    }

    public function meet() {
        return $this->belongsTo('App\Model\Meet','meet_id');
    }

    public function fullname() {
        $user = $this->user;
        return $user?$user->first_name.' '.$user->last_name:'__________';
    }

}

