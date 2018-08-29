<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
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
    public function order()
    {
        return $this->hasMany('App\Order');
    }
}
