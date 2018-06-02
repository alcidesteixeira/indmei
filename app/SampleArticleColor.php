<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SampleArticleColor extends Model
{
    public function wire()
    {
        $this->belongsTo('App\SampleArticleWire');
    }
}
