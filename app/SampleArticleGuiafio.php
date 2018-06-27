<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SampleArticleGuiafio extends Model
{
    /**
     * Get the comments for the blog post.
     */
    public function sampleArticles()
    {
        return $this->hasMany('App\SampleArticle');
    }
}
