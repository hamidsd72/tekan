<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\File;

class Connection extends Model {

    use SoftDeletes;
    

    public function export_user() {
        return $this->belongsTo('App\User','user_id');
    }

    public function photo() {
        return $this->morphOne('App\Model\Photo', 'pictures');
    }

}
