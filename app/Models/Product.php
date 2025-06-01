<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'title',
        'desc',
        'price',
        'stock',
        'date_made',
        'image_path',
        'size',
        'category_id',
        'category_name',
    ];

    public function category(){
        return $this->belongsTo(Category::class);
    }
    public function orders(){
        return $this->hasMany(Order::class);
    }

    use HasFactory;
}
