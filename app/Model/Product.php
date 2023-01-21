<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use File;

class Product extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function photo()
    {
        return $this->morphOne('App\Model\Photo', 'pictures');
    }

    public function category()
    {
        return $this->belongsTo('App\Model\Category', 'category_id');
    }
    public function creator()
    {
        return $this->belongsTo('App\User', 'creator_id');
    }

    public function factor()
    {
        return $this->belongsTo('App\Model\Factor')->withPivot('number_products');
    }
    public static function boot()
    {
        parent::boot();
        static::deleting(function($item){
            $item->photo()->get()->each(function($photo){
                $path = $photo->path;
                File::delete($path);
                $photo->delete();
            });
        });

    }
}

