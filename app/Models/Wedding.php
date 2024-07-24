<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wedding extends Model
{
    use HasFactory;
    public function packet()
    {
        return $this->belongsTo(Packet::class);
    }

    public function planning()
    {
        return $this->belongsTo(Planning::class);
    }

    public function guest()
    {
        return $this->hasMany(Guest::class);
    }

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
