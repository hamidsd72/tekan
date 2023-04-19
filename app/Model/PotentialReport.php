<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PotentialReport extends Model {
    protected $table = 'new_potential_reports';
    
    public $timestamps = false;

    // protected $fillable = [
    //     "potential_id",
    //     "hadaf_gozari_shakhsi",
    //     "hadaf_gozari_level",
    //     "candid_shabakesazi",
    //     "candid_forosh",
    //     "hadaf_gozari_shakhsi_val",
    //     "hadaf_gozari_level_val",
    //     "candid_shabakesazi_val",
    //     "candid_forosh_val",
    //     "date",
    // ];

    protected $fillable = [
        "potential_id",
        "status",       // pending, active, deactive
        "value",
        "column_name",  // hadaf_gozari_shakhsi, hadaf_gozari_level, candid_shabakesazi, candid_forosh
        "date",
    ];
    
    public function potential() {
        return $this->belongsTo('App\Model\Potential','potential_id');
    }

}

