<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Packet extends Model
{
    use HasFactory;
    public function packetAttachment()
    {
        return $this->hasMany(PacketAttachment::class);
    }

    public function wedding()
    {
        return $this->hasMany(Wedding::class);
    }

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
