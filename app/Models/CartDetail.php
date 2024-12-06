<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartDetail extends Model
{
    use HasFactory;

    protected $fillable = ['id_cart', 'size_id', 'product_quantity'];

    public function cart()
    {
        return $this->belongsTo(Cart::class, 'id_cart');
    }

    public function size()
    {
        return $this->belongsTo(SizeCode::class, 'size_id');
    }
}

