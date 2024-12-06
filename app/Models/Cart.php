<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = ['user_id'];

    public function details()
    {
        return $this->hasMany(CartDetail::class, 'id_cart');
    }

    public function product()
{
    return $this->belongsTo(Product::class, 'product_id');
}

}

