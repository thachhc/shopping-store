<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category_id',
        'tag_id',
        'brand_id',
        'price',
        'price_sale',
        'description',
        'status',
        'colorsize',
        'image'
    ];

    // Cast 'image' thành JSON array
    protected $casts = [
        'image' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function tag()
    {
        return $this->belongsTo(Tag::class);
    }

    
}
