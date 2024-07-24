<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailTransaction extends Model
{
    use HasFactory;
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function wedding()
    {
        return $this->belongsTo(Wedding::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function packet()
    {
        return $this->belongsTo(Packet::class);
    }

    public function planning()
    {
        return $this->belongsTo(Planning::class);
    }
}
