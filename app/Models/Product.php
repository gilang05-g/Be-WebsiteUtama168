<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'brand_id',
        'name',
        'slug',
        'description',
        'price',
        'discount_price',
        'stock',
        'location',
        'rating',
        'sold',
        'features',
        'type',  
    ];
    
    //relasi ke kategori
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    //relasi ke brand
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    //relasi ke gambar product
    public function images()
    {
        return $this->hasMany(ProductImage::class);   
    }
}