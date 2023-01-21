<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MonthlyPackage extends Model {

    use SoftDeletes;

    // protected $table = 'datas';

    protected $fillable = [
        "title",
        "status",
    ];
    // active pending

    public function reports() {
        return $this->hasMany('App\Model\MonthlyPackageReport','package_id');
    }
    
}
