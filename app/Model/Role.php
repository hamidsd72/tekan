<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'roles';

    public static function roles()
    {
        $user = auth()->user();

        if ($user->hasRole('مدیر')) {
            return  \App\Model\Role::all();
        }elseif($user->hasRole('نماینده مستقل')){
            return  \App\Model\Role::whereNotIn('name',['مدیر','نماینده مستقل'])->get();
        } else {
            return  \App\Model\Role::where('name','کاربر')->get();
        }
    }
}

