<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\File;

class QuadPerformance extends Model {
    
    use SoftDeletes;
    // protected $table = 'posts';

    protected $fillable = [
        "user_id",
        "label",  // گفتگو با محوریت توسعه ارتباطات , گفتگو با محوریت فروش یا مشتری نداری , گفتگو با محوریت شبکه سازی , گفتگو با محوریت رشد شخصی
        "name",
        "item_id",
        "date",
        "date_en",
        "status", // pending , active , deactive
    ];

    public function user() {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function item() {
        return $this->belongsTo('App\Model\Connection', 'item_id');
    }

}
