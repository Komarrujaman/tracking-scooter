<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Passenger extends Model
{
    use HasFactory;
    protected $fillable = ['scooter_id', 'name', 'duration', 'start', 'end', 'status'];

    public function scooters()
    {
        return $this->belongsTo(Scooter::class, 'scooter_id');
    }

    public function histories()
    {
        return $this->hasMany(History::class, 'passenger_id');
    }
}
