<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'order';

    protected $fillable = [
        'customer_id',
        'total_amount',
        'method_payment',
        'status',
    ];

    public function cart()
    {
        return $this->belongsTo(Cart::class, 'id_cart');
    }

    public function details()
    {
        return $this->hasMany(CartDetail::class, 'id_cart');
    }

    // public function orderDetails()
    // {
    //     return $this->hasMany(OrderDetail::class, 'order_id');
    // }

    // public function customer()
    // {
    //     return $this->belongsTo(User::class, 'customer_id');
    // }

    public function customer()
{
    return $this->belongsTo(User::class);
}

public function orderDetails()
{
    return $this->hasMany(OrderDetail::class);
}

}
