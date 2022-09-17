<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'img_url',
        'price',        
    ];
    
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
