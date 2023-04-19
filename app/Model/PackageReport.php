<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\File;
class PackageReport extends Model {

    use SoftDeletes;
    protected $guarded = ['id', 'created_at', 'updated_at'];


    public function customer() {
        return $this->belongsTo('App\Model\Customer','customer_id');
    }

}
