<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SizeCode extends Model
{
    use HasFactory;
    protected $table = 'code_sizes';
    protected $fillable = ['sizenumber', 'product_id', 'quantity']; // or whatever column holds the size number

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
