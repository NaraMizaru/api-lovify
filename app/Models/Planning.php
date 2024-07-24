<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Planning extends Model
{
    use HasFactory;
    public function wedding()
    {
        return $this->belongsTo(Wedding::class);
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

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
