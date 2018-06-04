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

    public function sampleArticleStatus()
    {
        return $this->belongsTo('App\SampleArticleStatus');
    }

    public function sampleArticleWires()
    {
        return $this->hasMany('App\SampleArticlesWire');
    }
}
