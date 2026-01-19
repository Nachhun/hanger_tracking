<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Scope for active brands
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Relationship with hanger entries
    public function hangerEntries()
    {
        return $this->hasMany(HangerEntry::class);
    }
}
