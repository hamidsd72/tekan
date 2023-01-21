<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\File;
class Package extends Model {
    use SoftDeletes;
    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $fillable = [
        "user_id",
        "product_id",
        "name",
        "count",
        "description",
    ];

    public function product() {
        return $this->belongsTo('App\Model\Product','product_id');
    }

    public function package_reports() {
        return $this->hasMany('App\Model\PackageReport','package_id','id')->where('user_id',auth()->user()->id);
    }
}
