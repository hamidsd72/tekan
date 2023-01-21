<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class Service extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];
    public function photo()
    {
        return $this->morphOne('App\Model\Photo', 'pictures');
    }
    public function file()
    {
        return $this->morphOne('App\Model\Filep', 'files');
    }
    public function video()
    {
        return $this->morphOne('App\Model\Video', 'videos');
    }
    public function user()
    {
        return $this->belongsTo('App\User','user_id')->first();
    }
    public function category()
    {
        return $this->belongsTo('App\Model\ServiceCat','category_id');
    }
    public function join()
    {
        return $this->belongsToMany('App\Model\ServicePackage', 'service_join_packages', 'service_id', 'package_id');
    }
    public static function join_id($id)
    {
        $join=ServiceJoinPackage::where('service_id',$id)->select('package_id')->get()->toArray();
        return $join;
    }
    public function levels()
    {
        return $this->hasMany('App\Model\ServiceLevel','service_id');
    }
    public function questions()
    {
        return $this->hasMany('App\Model\ServiceQuery','service_id');
    }
    public function questions_active()
    {
        return $this->hasMany('App\Model\ServiceQuery','service_id')->where('status','active');
    }
    public function plus()
    {
        return $this->hasMany('App\Model\ServicePlus','service_id');
    }
    public function plus_active()
    {
        return $this->hasMany('App\Model\ServicePlus','service_id')->where('status','active');
    }
    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($item) {
            $item->photo()->get()
                ->each(function ($photo) {
                    $path = $photo->path;
                    File::delete($path);
                    $photo->delete();
                });
//            $item->file()->get()
//                ->each(function ($file) {
//                    $path = $file->path;
//                    File::delete($path);
//                    $file->delete();
//                });
//            $item->video()->get()
//                ->each(function ($video) {
//                    $path = $video->path;
//                    File::delete($path);
//                    $video->delete();
//                });
        });
    }
}
