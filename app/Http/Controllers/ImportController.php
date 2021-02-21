<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use App\Helpers\AirportHelper;
use App\Helpers\RouteHelper;
use Validator;

class ImportController extends Controller
{
    public function __construct() 
    {
        $this->middleware('auth');
    }

    /**
     * Handle an incoming import request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    static public function import(Request $request)
    {
        $request->validate([
            'airports' => 'required|mimes:txt,csv,application/octet-stream,bin',
            'routes' => 'required|mimes:txt,csv,application/octet-stream,bin',
        ]);

        // Import airports first
        try {
            $airports = $request->file('airports');
            $nameA = 'airports' . '.' . $airports->getClientOriginalExtension();
            $airports->storeAs('imports', $nameA);
            AirportHelper::import($nameA);

            // // Import Routes
            $routes = $request->file('routes');
            $nameR = 'routes' . '.' . $routes->getClientOriginalExtension();
            $routes->storeAs('imports', $nameR);
            RouteHelper::import($nameR);
        } catch (\Exception $e) {
            $e->getMessage();
            return $e->getMessage();
        }

        unlink(storage_path('app/imports/'.$nameA));
        unlink(storage_path('app/imports/'.$nameR));

        return view('components.admin-action-page');
    }

    public function importAirports(Request $request)
    {
        $validator = Validator::make($request->all(), 
        [ 
            'airports' => 'required|mimes:txt,csv,application/octet-stream,bin',
        ]);
        if ($validator->fails()) {          
            return response()->json(['error'=>$validator->errors()], 401);                        
        }  
        try {
            $airports = $request->file('airports');
            $nameA = 'airports' . '.' . $airports->getClientOriginalExtension();
            $airports->storeAs('imports', $nameA);
            AirportHelper::import($nameA);
        } catch (\Exception $e) {
            $e->getMessage();
            return $e->getMessage();
        }

        unlink(storage_path('app/imports/'.$nameA));
        $msg = "Airport file uploaded successfully";
        return response()->json(['message' => $msg], 200);
    }

    public function importRoutes(Request $request)
    {
        $validator = Validator::make($request->all(), 
        [ 
            'routes' => 'required|mimes:txt,csv,application/octet-stream,bin',
        ]);
        if ($validator->fails()) {          
            return response()->json(['error'=>$validator->errors()], 401);                        
        }  
        try {
            $routes = $request->file('routes');
            $nameR = 'routes' . '.' . $routes->getClientOriginalExtension();
            $routes->storeAs('imports', $nameR);
            RouteHelper::import($nameR);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 300);
        }

        unlink(storage_path('app/imports/'.$nameR));
        $msg = "Route file uploaded successfully";
        return response()->json(['message' => $msg], 200);
    }
}
