<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\File;

class Following extends Model
{
    protected $table = 'following';
    
    use SoftDeletes;

    protected $fillable = [
        "user_id",
        "potential_id",
    ];

}
 