<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\OrderStatus;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'customer_name',
        'customer_email',
        'customer_address',
    ];

    protected $casts = [
        'status' => OrderStatus::class,
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
