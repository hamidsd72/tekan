<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PackageBuy extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];
    public function package()
    {
        return $this->belongsTo('App\Model\Package','package_id');
    }
    public function project()
    {
        return $this->belongsTo('App\Model\Project','project_id');
    }

    public function getFullPriceAttribute()
    {
        if ($this->price_type == 'fixed')
            return number_format($this->price) .' تومان ';

        return $this->price .' درصد ';

    }


}
