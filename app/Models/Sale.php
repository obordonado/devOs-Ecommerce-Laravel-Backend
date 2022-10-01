<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable =
    [
        'user_id',
        'total_price',
        'rating',
        'status'
    ];
    
    public function users()
    {
        return $this->belongsTo(User::class);
    }

    public function sales()
    {
        return $this->hasMany(ProductSale::class);
    }
}
