<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scooter extends Model
{
    use HasFactory;
    protected $fillable = ['scooter', 'app', 'dev_eui', 'dev_name', 'status'];

    public function passengers()
    {
        return $this->hasMany(Passenger::class, 'scooter_id');
    }
}
