<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable =
    [
        'sale_id',
        'product_id',
        'quantity',
        'price'
    ];

    public function users()
    {
        return $this->belongsTo(Product::class);
    }

    public function sales()
    {
        return $this->belongsTo(Sale::class);
    }
}
