<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Airport extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function startRoutes()
    {
        return $this->hasMany('App\Models\Route','source_airport_id', 'airport_id');
    }

    public function endRoutes()
    {
        return $this->hasMany('App\Models\Route','destination_airport_id', 'airport_id');
    }
}
