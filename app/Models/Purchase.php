<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable =
    [
        'user_id',
        'sale_id',
        'product_id',
        'quantity',
        'price'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function sales()
    {
        return $this->belongsTo(Sale::class);
    }

    public function purchases()
    {
        return $this->belongsTo(User::class);
    }
}
