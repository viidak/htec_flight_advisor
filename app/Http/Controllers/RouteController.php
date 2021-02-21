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
            // 'city_from' => 'string|max:255|required|regex:/^[a-zA-Z]+$/u',
            // 'city_to' => 'string|max:255|required|regex:/^[a-zA-Z]+$/u',
            'city_to_id' => 'required|numeric|min:1',
            'city_from_id' => 'required|numeric|min:1',
        ]);

        $results = [];

        
        // Check if there are Airports in given cities
        // $column = 'city';
        // $cityA = $request->city_from;
        // $cityB = $request->city_to;

        // $startAirports = Airport::where($column, $cityA)->get();
        // $endAirports = Airport::where($column, $cityB)->get();

        $column = 'airport_id';
        $startAirports = Airport::where($column, $request->city_from_id)->get();
        $endAirports = Airport::where($column, $request->city_to_id)->get();
        
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
            $endRoutesList[] = Route::where('destination_airport_id', $endAirport->airport_id)->get();
        }

        $allRoutes = Route::all();
        $allRoutes = $allRoutes->keyBy('route_id');

        $routes = [];
        $second = [];
        $remainingRoutes = [];
        foreach ($startRoutesList as $startRoutes)
        {
            foreach ($startRoutes as $startRoute)
            {
                foreach ($endRoutesList as $endRoutes)
                {
                    foreach ($endRoutes as $endRoute)
                    {
                        // First round
                        $foundRoutes = $allRoutes->filter(function($route) use($startRoute, $endRoute) {
                            if ($route->source_airport_id == $startRoute->source_airport_id
                                && $route->destination_airport_id == $endRoute->destination_airport_id)
                            {
                                return $route;
                            }
                        });
                        array_push ($routes, $foundRoutes);

                        foreach ($foundRoutes as $found) {
                            $allRoutes->forget($found->route_id);
                        }

                        // Next start and end airports
                        $nextStart = [];
                        $nextEnd = [];
                        if ($startRoute->destination_airport_id !== $endRoute->destination_airport_id) {
                            $nextStart = $allRoutes->filter(function($route) use($startRoute) {
                                if ($route->source_airport_id == $startRoute->destination_airport_id)
                                {
                                    return $route;
                                }
                            });
                        }

                        if ($startRoute->source_airport_id !== $endRoute->source_airport_id) {
                            $nextEnd = $allRoutes->filter(function($route) use($endRoute) {
                                if ($route->destination_airport_id == $endRoute->source_airport_id)
                                {
                                    return $route;
                                }
                            });
                        }

                        // Second Round
                        $foundSecond = $allRoutes->filter(function($route) use($startRoute, $endRoute) {
                            if ($route->source_airport_id == $startRoute->source_airport_id
                                && $route->destination_airport_id == $endRoute->destination_airport_id)
                            {
                                return $route;
                            }
                        });

                        foreach ($foundSecond as $found) {
                            $allRoutes->forget($found->route_id);
                        }
                        array_push ($second, $foundSecond);
                    }
                }
            }
        }
        dd($routes, $second);

        return response()->json($results, 200);
    }
}
