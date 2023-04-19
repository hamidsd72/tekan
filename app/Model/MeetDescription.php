<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MeetDescription extends Model {

    use SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $fillable = [
        "meet_id",
        "description",
    ];

    public function creator() {
        return $this->belongsTo('App\User','creator_id');
    }


}

