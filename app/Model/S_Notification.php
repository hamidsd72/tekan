<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class Notification extends Model
{
    protected $table = 'notifications';

    protected $fillable = [
        "id",
        "creator_id",
        "user_id",
        "status",
        "subject",
        "description",
        "atach",
        "created_at",
        "updated_at",
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function creator()
    {
        return $this->belongsTo('App\User', 'creator_id');
    }

    public static function statuses($status = null)
    {
        $statuses = [
            'read'=>'خوانده شده',
            'unread'=>'خوانده نشده',
        ];

        if (!is_null($status)) {
            if(isset($statuses[$status]))
                return $statuses[$status];

            return '-';
        }

       return $statuses;
    }
}

