<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    // Các trường có thể mass-assignable
    protected $fillable = [
        'user_id',
        'address_line1',
        'address_line2',
        'city',
        'state',
        'postal_code',
        'phone_number',
        'is_default',
    ];

    // Mối quan hệ với model User (nếu có)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
