<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PacketAttachment extends Model
{
    use HasFactory;
    public $timestamps = false;
    public function packet()
    {
        return $this->belongsTo(Packet::class);
    }

    public function catering()
    {
        return $this->belongsTo(Catering::class);
    }

    public function decoration()
    {
        return $this->belongsTo(Decoration::class);
    }

    public function mua()
    {
        return $this->belongsTo(Mua::class);
    }

    public function photographer()
    {
        return $this->belongsTo(Photographer::class);
    }

    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }
}
