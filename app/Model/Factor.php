<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Factor extends Model {

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $fillable = [
        "user_id",
        "customer_id",
        "product_id",
        "count",
        "total",
        "text",
        "time",
        "time_en",
        "deleted_at",
    ];

    public function creator()
    {
        return $this->belongsTo('App\User','creator_id');
    }
    public function customer()
    {
        return $this->belongsTo('App\Model\Customer','customer_id','id');
    }
    public function products()
    {
        return $this->belongsToMany('App\Model\Product')->withPivot('number_products');
    }

    public function product() {
        return $this->belongsTo('App\Model\Product','product_id');
    }


    public static function getFactors($date = null)
    {
        $auth = auth()->user();

        if (is_null($date)) {
            $factors = new Factor();
        }else{
            $factors = Factor::whereDate('follow_date', $date);
        }

        if ($auth->hasRole('مدیر')) {
            return $factors->get();
        }elseif($auth->hasRole('نماینده مستقل')){
            //todo check later
            $underUsers =  User::getSubCategoryUsersFromId($auth->id);
            if ($underUsers) {
                $factors->whereIn('creator_id',$underUsers->pluck('id'))->get();
            }
            return null;
        }elseif($auth->hasRole('کاربر')){
            //todo check later
            $factors->where('creator_id',$auth->id)->get();
        }else{
            return null;
        }

    }

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($item) {
                $item->products()->detach();
        });
    }
}

