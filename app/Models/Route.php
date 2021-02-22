<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function sourceAirport()
    {
        return $this->belongsTo('App\Models\Airport', 'source_airport_id', 'airport_id');
    }

    public function destinationAirport()
    {
        return $this->belongsTo('App\Models\Airport', 'destination_airport_id', 'airport_id');
    }
}
