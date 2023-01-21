<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\MonthlyPackage;

class Potential extends Model {
    
    public $timestamps = false;

    protected $fillable = [
        "user_id",
        "name",
        "present_ta_peresent",
        "kasb_o_kar_kochak_ya_bozorg",
        "present_ta_estage",
        "hadaf_gozari_shakhsi",
        "folowe_ya_4eqdam",
        "hadaf_gozari_level",
        "hadaf_jam_daramad_mah",
        "candid_shabakesazi",
        "candid_forosh",
    ];

    public function admin() {
        return $this->belongsTo('App\User','user_id');
    }

    public function user() {
        return $this->belongsTo('App\User','name');
    }
    
    public function potential_packages() {
        return $this->hasMany('App\Model\MonthlyPackageReport','potential_id');
    }

    public function potential_candid() {
        $monthlyPackage = MonthlyPackage::where('status','active')->first('id');
        return $this->potential_packages()->where('status','!=','deleted')->where('package_id',$monthlyPackage->id)->first();
    }

    public function full_name() {
        $user = $this->user;
        return $user?$user->first_name.' '.$user->last_name:'__________';
    }

}
