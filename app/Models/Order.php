<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

    protected $fillable = [
        
        ];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function products(){
        return $this->hasMany(Product::class);
    }
}
