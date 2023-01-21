<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class ServicePackage extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];
    public function photo()
    {
        return $this->morphOne('App\Model\Photo', 'pictures')->where('place',null);
    }
    public function photo_inner_page()
    {
        return $this->morphOne('App\Model\Photo', 'pictures')->where('place','inner_page');
    }
    public function program()
    {
        return $this->morphOne('App\Model\Photo', 'pictures')->where('place','program');
    }
    public function file()
    {
        return $this->morphOne('App\Model\Filep', 'files');
    }
    public function video()
    {
        return $this->morphOne('App\Model\Video', 'videos');
    }
    public function video_learn()
    {
        return $this->morphMany('App\Model\Video', 'videos')->where('status','active')->orderBy('sort','asc');
    }

    public function join()
    {
        return $this->belongsToMany('App\Model\Service', 'service_join_packages', 'package_id', 'service_id')->orderBy('order');
    }
    public function joins()
    {
        return $this->hasMany('App\Model\ServiceJoinPackage','package_id')->orderBy('sort_by');
    }
    public function category()
    {
        return $this->belongsTo('App\Model\ServiceCat','category_id');
    }
    public function prices()
    {
        return $this->hasMany('App\Model\ServicePackagePrice','package_id')->where('status','active')->orderBy('month_time','asc');
    }
    public function sales()
    {
        if(auth()->check())
        {
            return $this->hasOne('App\Model\ServiceFactor','package_id')->where('user_id',auth()->user()->id)->where('type','package')->where('pay_status','paid');
        }
    }
    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($item) {
            $path = $item->pic_card;
            File::delete($path);
            $item->photo()->get()
                ->each(function ($photo) {
                    $path = $photo->path;
                    File::delete($path);
                    $photo->delete();
                });
            $item->photo_inner_page()->get()
                ->each(function ($photo) {
                    $path = $photo->path;
                    File::delete($path);
                    $photo->delete();
                });
            $item->program()->get()
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

    public function getSummaryAttribute()
    {
        $text = $this->text;
        $text = strip_tags($text);
        $text = trim(preg_replace('/\s\s+/', ' ', $text));
        $text = ltrim(rtrim($text));

        $string = (strlen($text) > 200) ? substr($text, 0, 200) . '...' : $text;
        return $string;
    }
}
