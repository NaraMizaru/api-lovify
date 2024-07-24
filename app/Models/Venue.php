<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{
    use HasFactory;
    public function planning()
    {
        return $this->belongsTo(Planning::class);
    }

    public function packet()
    {
        return $this->belongsTo(Packet::class);
    }

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
