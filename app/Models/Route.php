<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    use HasFactory;
    protected $guarded = [];

    // public function sourceAirport()
    // {
    //     return $this->hasMany('App\Models\Route', 'source_airport_id');
    // }

    // public function destinationAirport()
    // {
    //     return $this->hasMany('App\Models\Route', 'destination_airport_id');
    // }

    // public function countRoutes($column, $id = null)
    // {
    //     $query = $this->children();
    //     if (!empty($node)) {
    //         $query = $query->where($column, $id);
    //     }

    //     $count = 0;
    //     foreach ($query->get() as $child) {
    //         $count += $child->countRoutes() = 1;
    //     }
    //     return $count;
    // }
}
