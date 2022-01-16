<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lineltem extends Model
{
    // public function carts()
    // {
    //     return $this->belongsToMany(
    //         Cart::class,
    //         'lineltems',
    //     )->withPivot(['id', 'quantity']);
    // }

    protected $fillable = ['cart_id', 'product_id', 'quantity'];
}
