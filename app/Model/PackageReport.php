<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PackageReport extends Model {

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $fillable = [
        "user_id",
        "package_id",
        "customer_id",
        "count",
        "status",
        "description",
        "time",
        "time_en",
    ];

    public function customer() {
        return $this->belongsTo('App\Model\Customer','customer_id')->first(['id','name']);
    }

}
