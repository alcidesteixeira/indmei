<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public function suppliers()
    {
        return $this->hasMany('App\Supplier');
    }
}
