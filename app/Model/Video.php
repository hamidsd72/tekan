<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Video extends Model {

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function videos() {
        return $this->morphTo();
    }

    public static function type($type) {
        switch ($type){
            case 'active':
                return '<span class="badge bg-success">فعال</span>';
                break;
            case 'pending':
                return '<span class="badge bg-danger">غیرفعال</span>';
                break;
            default:
                return '';
                break;
        }
    }

    
}
