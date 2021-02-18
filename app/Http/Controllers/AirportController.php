<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Airport;

class AirportController extends Controller
{
    public function __construct() 
    {
        $this->middleware('auth');
    }

    public static function getIdList()
    {
        $idList = [];
        $idList = Airport::where('airport_id', '>', 0)->pluck('airport_id')->toArray();

        return $idList;
    }
}
