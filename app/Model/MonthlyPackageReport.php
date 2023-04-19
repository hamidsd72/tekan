<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MonthlyPackageReport extends Model {

    use SoftDeletes;

    // protected $table = 'datas';

    protected $fillable = [
        "package_id",
        "user_id",
        "potential_id",
        "status",
    ];
    // active pending block deleted
    
    
    public function package() {

        return $this->belongsTo('App\Model\MonthlyPackage','package_id');
    }
    
    public function potential() {
        return $this->belongsTo('App\Model\Potential','potential_id');
    }

}
