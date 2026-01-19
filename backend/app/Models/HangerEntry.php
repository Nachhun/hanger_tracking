<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HangerEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        // New required fields
        'outlet_name',
        'province_id',
        'brand_id',
        'hanger_id',
        // Legacy fields (optional)
        'customer_name',
        'quantity',
        'location',
        'notes',
        'photo_path',
        'latitude',
        'longitude',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
}
