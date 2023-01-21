<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Data extends Model
{
    protected $table = 'datas';

    protected $fillable = [
        "page_name",
        "title",
        "text",
        "section",
        "sort",
        "link",
        "pic",
        "status",
        "last_modify_user_id",
    ];
}
