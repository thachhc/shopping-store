<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    // The table associated with the model.
    protected $table = 'order_details';

    // The primary key associated with the table.
    protected $primaryKey = 'id';

    // Whether the primary key is auto-incrementing.
    public $incrementing = true;

    // The data type of the primary key.
    protected $keyType = 'int';

    // Fields that can be mass-assigned.
    protected $fillable = [
        'order_id',
        'id_cart_detail',
    ];

    // Define the relationship with the Order model.
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    // Define the relationship with the CartDetail model.
    public function cartDetail()
    {
        return $this->belongsTo(CartDetail::class, 'id_cart_detail');
    }

    public function codeSize()
    {
        return $this->belongsTo(SizeCode::class, 'id_cart_detail');
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
