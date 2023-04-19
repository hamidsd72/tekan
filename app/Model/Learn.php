<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Learn extends Model {
    
    use SoftDeletes;

    protected $fillable = [
        "title",
        "role",
        "user_id",
        "sort",
        "status", // active , pending
    ];

    public function videos() {
        return $this->morphMany('App\Model\Video', 'videos');
    }

    public function links() {
        return $this->hasMany('App\Model\Link','item_id')->where('model','App\Model\Learn');
    }

    public static function roles($role) {
        $list   = null;
        $roles  = \App\Model\Role::whereIn('id', explode(',',$role))->get('title');
        foreach ($roles as $role) $list = $list.$role->title.' - ';
        if ( strlen($list) > 3 ) return substr($list, 0, (strlen($list)-3));
        return $list;
    }

    // public static function create_video($title, $path=null, $link=null) {
    //     $item   = new \App\Model\Video;
    //     $item->item_id      = $this->id;
    //     $item->model_name   = 'App\Model\Learn';
    //     $item->title        = $title;
    //     if (!$path===null) $item->path = $path;        
    //     if (!$link===null) $item->link = $link;
    //     $item->save();
    //     return $item;
    // }

}
