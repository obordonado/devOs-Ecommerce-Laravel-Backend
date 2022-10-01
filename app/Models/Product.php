<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = 
    [
        'user_id',
        'brand',
        'name',
        'img_url',
        'price'
    ];
    
    public function users()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->hasMany(ProductSale::class);
    }
}
