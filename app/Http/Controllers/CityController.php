<?php

namespace App\Http\Controllers;

use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use App\Models\City;
use App\Models\Comment;

class CityController extends Controller
{
    public function __construct() 
    {
        $this->middleware('auth');
    }
    
    /**
     * Handle an incoming add city request.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function addCity(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);

        City::create([
            'name' => $request->name,
            'country' => $request->country,
            'description' => $request->description,
        ]);

        return view('components.admin-action-page');
    }

    /**
     * Handle an incoming search city request.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function search(Request $request)
    {
        $request->validate([
            'name' => 'string|max:255|nullable',
            'comments' => 'numeric|min:0|nullable',
        ]);

        $commNum = $request->comments ?? 0;
        $query = City::with('comments')->orderBy('name', 'asc');

        switch ($request->search_action) {
            case 'search_one':
                $query->where('name', 'LIKE', "%{$request->name}%");
                break;

            case 'search_all':
                // no additional queries needed
                break;
            
            default:
                return 'Search Initiated with no valid search action';
                break;
        }

        $results = $query->get()->map(function($city) use($commNum) {
            $city->setRelation('comments', $city->comments->take($commNum));
            return $city;
        });

        return view('components.city', compact('results'));
    }
}
