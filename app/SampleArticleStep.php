<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SampleArticleStep extends Model
{
    public function sampleArticleWires()
    {
        return $this->hasMany('App\SampleArticleWire');
    }
}
