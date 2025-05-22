<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType ='string';

    protected static function booted()
    {
        static::creating(function ($product) {
            $product->id = (string) Str::uuid();
        });
    }
    protected $fillable = ['name', 'description', 'photo', 'price'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
