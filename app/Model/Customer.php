<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\File;

class Customer extends Model {
    use SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at'];


    public function export_user() {
        return $this->belongsTo('App\User','user_id');
    }
    public function referrer_users() {
        return $this->hasMany('App\Model\Customer','referrer_id');
    }
    public function referrer() {
        return $this->belongsTo('App\Model\Customer','referrer_id');
    }

    public function admin_factors() {
        return $this->hasMany('App\Model\Factor','user_id');
    }

    public function customer_factors() {
        return $this->hasMany('App\Model\Factor','customer_id');
    }

    public function state() {
        return $this->belongsTo('App\Model\ProvinceCity','state_id');
    } 
    
    public function city() {
        return $this->belongsTo('App\Model\ProvinceCity','city_id');
    }
    
    public function grade($lev) {
        if ($lev > 5) return 'مشتری هوادار';
        elseif ($lev > 2 && $lev < 6) return 'مشتری وفادار';
        elseif ($lev==2) return 'مشتری راضی';
        elseif ($lev==1) return 'مشتری من';
        else return $lev;
    }
}
