<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\File;

class LabelPerformance extends Model {
    
    use SoftDeletes;

    protected $fillable = [
        "access",
        "label",
        "sort",
        "status", // active , deactive
    ];

}
