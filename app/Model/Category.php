<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use File;

class Category extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];


    public function parent()
    {
        return $this->belongsTo('App\Model\Category', 'parent_id');
    }
    
    public function products()
    {
        return $this->hasMany('App\Model\Product', 'category_id');
    }

}
