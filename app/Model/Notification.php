<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class Notification extends Model
{
    protected $table = 'notifications';

    protected $fillable = [
        "id",
        "type",
        "notifiable_type",
        "notifiable_id",
        "data",
        "created_at",
        "updated_at",
        "read_at",
    ];

    public static function setItem($type, $notifiable_type, $notifiable_id, $data) {
        $timeNow    = \Carbon\Carbon::now();
        $notify = new Notification();
        $notify->id                 = Str::random(36);
        $notify->user_id            = auth()->user()->id;
        $notify->type               = $type;
        $notify->notifiable_type    = $notifiable_type;
        $notify->notifiable_id      = $notifiable_id;
        $notify->data               = $data;
        $notify->created_at         = $timeNow;
        $notify->updated_at         = $timeNow;
        $notify->save();
        return $notify;
    }

    public static function setItemByUserId($type, $notifiable_type, $notifiable_id, $data, $id) {
        $timeNow    = \Carbon\Carbon::now();
        $notify = new Notification();
        $notify->id                 = Str::random(36);
        $notify->user_id            = $id;
        $notify->type               = $type;
        $notify->notifiable_type    = $notifiable_type;
        $notify->notifiable_id      = $notifiable_id;
        $notify->data               = $data;
        $notify->created_at         = $timeNow;
        $notify->updated_at         = $timeNow;
        $notify->save();
        return $notify;
    }

    public function markAsRead()
    {
        if (is_null($this->read_at)) {
            $this->forceFill(['read_at' => $this->freshTimestamp()])->save();
        }
    }
    
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

