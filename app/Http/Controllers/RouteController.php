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
            'city_from' => 'string|max:255|required',
            'city_to' => 'string|max:255|required',
        ]);

        // Check if there are Airports in given cities
        $column = 'city';
        $cityA = $request->city_from;
        $cityB = $request->city_to;

        $airports = Airport::whereIn($column, [$cityA, $cityB])->get();
        if ($airports->count() !== 2) {
            return response()->json(['error' => 'No Airport found in one of the cities'], 404);
        }
        $sourceAirport = $airports[0];
        $destinationAirport = $airports[1];
        $said = $sourceAirport->airport_id;
        $daid = $destinationAirport->airport_id;

        $airportIdList = Airport::where('airport_id', '>', 0)->pluck('airport_id')->toArray();
        $datas = Route::select('source_airport_id', 'destination_airport_id', 'price')->take(10)->get();
        $subset = $datas->map(function ($data) {
            return collect($data->toArray())
                ->only(['source_airport_id', 'destination_airport_id', 'price'])
                ->all();
        });
        // $data = collect($data->toArray())->flatten()->all();
        dd($subset);
        // Check if there is a direct route

        $routes = Route::where('source_airport_id', '!=', $said)->where('destination_airport_id', '!=', $daid)->get();
        // dd($routes);
        $roots = Route::where('source_airport_id', $said)->get();
        $goals = Route::where('destination_airport_id', $daid)->get();

        // $flights = Route::select('routes.route_id','routes.destination_airport_id','dep_air.name','routes.source_airport_id','arr_air.name','routes.price')
        //     ->join('airports as dep_air','routes.destination_airport_id','dep_air.airport_id')
        //     ->join('airports as arr_air' ,'routes.source_airport_id','arr_air.airport_id')
        //     ->where('dep_air.airport_id','=', $said)
        //     ->where('arr_air.airport_id','=', $daid)
        //     ->get();
        // dd($flights);
        // $stack = ['routes' => []];

        // foreach ($roots as $root) {
        //     $child = [$root];

        //     foreach ($routes as $route) {
        //         $previous = $child[count($child) - 1];

        //         if ($route->source_airport_id == $previous->destination_airport_id) {
        //             array_push($child, $route);

        //             foreach ($goals as $goal) {
        //                 if ($goal->source_airport_id == $route->destination_airport_id) {
        //                     array_push($child, $goal);
        //                     array_push($stack['routes'], $child);
        //                     break 2;
        //                 }
        //             }
        //         }
        //     }
        // }

        // dd($stack);
        // return $stack;

        return view('components.route', compact('sourceAirport', 'destinationAirport'));
    }
}
