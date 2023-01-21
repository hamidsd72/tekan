<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ProvinceCity extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['parent_id', 'name'];

    public function parent()
    {
        return $this->hasOne('App\Model\ProvinceCity', 'parent_id');
    }

    public function children()
    {
        return $this->hasMany('App\Model\ProvinceCity', 'parent_id')->with('children');
    }

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($category) {
            $category->children()->get()->each(function ($children) {
                $children->delete();
            });
        });
    }

}
