<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'address_id',
        'pet_id',
        'pet_price',
        'quantity', 
        'shipping_cost',
        'tax',
        'total',
    ];

    // Liên kết với bảng addresses
    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    // Liên kết với bảng pets
    public function pet()
    {
        return $this->belongsTo(Pet::class);
    }
}