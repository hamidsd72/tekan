<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\User;

class OffCode extends Model {
    protected $guarded = ['id', 'created_at', 'updated_at'];
    public static function user($type) {
        switch ($type) {
            case '0':
                return 'همه کاربران';
                break;
            default:
                $user = User::find($type);
                if($user) {
                    return $user->first_name.' '.$user->last_name;
                } else {
                    return 'کاربر پاک شده';
                }
                break;
        }
    }

    public function item() {
        return $this->belongsTo('App\Model\ServicePackage','item_id')->first('title');
    }
}
