<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SampleArticle extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'reference', 'description', 'status_id',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function SampleArticlesStatus()
    {
        return $this->belongsTo('App\SampleArticlesStatus');
    }

    public function sampleArticleWires()
    {
        return $this->hasMany('App\SampleArticleWire');
    }
}
