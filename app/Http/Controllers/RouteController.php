<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Airport;
use App\Models\Route;

class RouteController extends Controller
{
    public function __construct() 
    {
        $this->middleware('auth');
    }

    /**
     * Handle an incoming search route request.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function search(Request $request)
    {
        $request->validate([
            'city_from' => 'string|max:255|required|regex:/^[a-zA-Z]+$/u',
            'city_to' => 'string|max:255|required|regex:/^[a-zA-Z]+$/u',
        ]);

        $results = [];

        return view('components.route', compact('results'));
    }

    /**
     * Handle an incoming search route request.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function findRoutes(Request $request)
    {
        $request->validate([
            'city_from' => 'string|max:255|required|regex:/^[a-zA-Z]+$/u',
            'city_to' => 'string|max:255|required|regex:/^[a-zA-Z]+$/u',
        ]);

        $results = [];

        
        // Check if there are Airports in given cities
        $column = 'city';
        $cityA = $request->city_from;
        $cityB = $request->city_to;

        $startAirports = Airport::where($column, $cityA)->get();
        $endAirports = Airport::where($column, $cityB)->get();
        
        if (!$startAirports || !$endAirports)
        {
            return response()->json(['error' => 'No Airport found in one of the cities'], 404);
        }

        $startRoutesList = [];
        foreach ($startAirports as $startAirport)
        {
            $startRoutesList[] = Route::where('source_airport_id', $startAirport->airport_id)->get();
        }
        $endRoutesList = [];
        foreach ($endAirports as $endAirport)
        {
            $endRoutesList[] = Route::where('source_airport_id', $endAirport->airport_id)->get();
        }

        $allRoutes = Route::all();
        $dirrectRoutes = [];
        foreach ($startRoutesList as $startRoutes)
        {
            foreach ($startRoutes as $startRoute)
            {
                foreach ($endRoutesList as $endRoutes)
                {
                    foreach ($endRoutes as $endRoute)
                    {
                        $dirrectRoutes[] = $allRoutes->filter(function($route) {

                        });
                    }
                }
            }
        }

        return response()->json($results, 200);
    }
}
