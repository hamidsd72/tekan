<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\MonthlyPackage;
use App\Model\PotentialReport;
use Carbon\Carbon;

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

    public function potential_candid_New($monthlyPackage) {
        return $this->potential_packages()->where('status','!=','deleted')->where('package_id',$monthlyPackage->id)->first();
    }

    public function potential_candid() {
        $monthlyPackage = MonthlyPackage::where('status','active')->first('id');
        return $this->potential_packages()->where('status','!=','deleted')->where('package_id',$monthlyPackage->id)->first();
    }

    public function potential_reports() {
        return $this->hasMany('App\Model\PotentialReport','potential_id');
    }

    public function potential_report_month() {
        return $this->hasMany('App\Model\PotentialReport','potential_id')->where('date','>',start_en());
    }

    // public function potential_report_findOrCreate() {
    //     $reports = $this->potential_report_month();
    //     if ($reports->count() < 1) {
    //         $this->update([
    //             'hadaf_gozari_shakhsi'  => null,
    //             'hadaf_gozari_level'    => null,
    //             'candid_shabakesazi'    => null,
    //             'candid_forosh'         => null
    //         ]);

    //         PotentialReport::create([
    //             'potential_id'  => $this->id,
    //             'date'          => Carbon::today()
    //         ]);
    
    //         return $this->potential_report_month();
    //     }
    //     return $reports;
    // }

    public function potential_report_findNew($column) {
        return $this->potential_report_month()->where('column_name' ,$column)->where('status', 'active')->get();
    }

    public function potential_report_find($column) {
        return $this->potential_report_month()->where('column_name' ,$column)->where('status', 'active')->count();
    }

    public function potential_report_findOrCreate($column, $value) {
        // گزارش های این ماه
        $reports    = $this->potential_report_month();
        // اگر در ماه جاری گزارش نداشت آیتم رو خالی کن
        if ($reports->count() < 1) {
            $this->update([
                'hadaf_gozari_shakhsi'  => null,
                'hadaf_gozari_level'    => null,
                'candid_shabakesazi'    => null,
                'candid_forosh'         => null
            ]);
        }
        // گزارش با ستون خالی برای ثبت گزارش آیتم 
        $report     = $reports->where('column_name' ,$column)->where('status', 'pending')->first();
        if ($report) {
            return $report;
        }
        else {
            // اگر ستون خالی برای آیتم نداشت ی گزارش بساز
            if ($value) {
                $new_report = PotentialReport::create([
                    'potential_id'  => $this->id,
                    'column_name'   => $column,
                    'status'        => 'pending',
                    'value'         => $value,
                    'date'          => Carbon::today()
                ]);
            }
            return $new_report;
        }
    }

    public function full_name() {
        $user = $this->user;
        return $user?$user->first_name.' '.$user->last_name:'__________';
    }

}

