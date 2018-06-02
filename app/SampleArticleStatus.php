<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SampleArticleStatus extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'status',
    ];

    /**
     * Get the comments for the blog post.
     */
    public function sampleArticles()
    {
        return $this->hasMany('App\SampleArticle');
    }
}
