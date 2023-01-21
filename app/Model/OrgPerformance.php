<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\File;

class OrgPerformance extends Model {
    
    use SoftDeletes;

    protected $fillable = [
        "user_id",
        "label_id",
        "name",
        "date",
        "date_en",
        "status", // pending , active , deactive
    ];

    public function user() {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function label() {
        return $this->belongsTo('App\Model\LabelPerformance', 'label_id');
    }

}
