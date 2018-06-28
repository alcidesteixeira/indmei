<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public function supplier()
    {
        return $this->belongsTo('App\Supplier');
    }

    public function client()
    {
        return $this->belongsTo('App\Client');
    }

}
