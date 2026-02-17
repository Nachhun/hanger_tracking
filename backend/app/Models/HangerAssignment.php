<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HangerAssignment extends Model
{
    protected $fillable = [
        'user_id',
        'admin_id',
        'quantity',
        'note',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
