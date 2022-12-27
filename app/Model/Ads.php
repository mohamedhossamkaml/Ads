<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;


class Ads extends Model
{
    protected $table    ='ads';
    protected $fillable =
    [
        'title',
        'description',
        'type',
        'advertiserid',
        'category_id',
        'tags_id',
        'start_date',
    ];

    public function advertiser()
    {
        return $this->hasOne('App\User','id','advertiserid');
    }
    public function category()
    {
        return $this->hasOne('App\Model\Category','id','category_id');
    }
    public function tagsID()
    {
        return $this->hasMany('App\Model\Tags','id','tags_id');
    }
}
