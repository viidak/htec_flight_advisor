<?php

namespace App\Http\Controllers;

use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use App\Helpers\ApiHelper;
use Validator;
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

    public function searchCity(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255|regex:/^[a-zA-Z]+$/u',
            'numberOfComments' => 'numeric|min:0|nullable',
        ]);

        $numberOfComments = $request->numberOfComments ?? 0;

        $query = City::with('comments')->orderBy('name', 'asc')->where('name', 'LIKE', "%{$request->name}%");
        $results = $query->get()->map(function($city) use($numberOfComments) {
            $city->setRelation('comments', $city->comments->take($numberOfComments));
            return $city;
        });

        return $results;
    }

    public function index(Request $request)
    {
        $request->validate([
            'numberOfComments' => 'numeric|min:0|nullable',
        ]);

        $numberOfComments = $request->numberOfComments ?? 0;

        $query = City::with('comments')->orderBy('name', 'asc');
        $results = $query->get()->map(function($city) use($numberOfComments) {
            $city->setRelation('comments', $city->comments->take($numberOfComments));
            return $city;
        });
        return $results;
    }
 
    public function show(City $city)
    {
        return $city;
    }

    public function store(Request $request)
    {
        $dataArray = $request->json()->all();
        
        if (empty($dataArray)) {
            return response()->json(["error" => "No Data provided"], 404);
        }

        $request->validate([
            '*.name' => 'required|string|max:255',
            '*.country' => 'required|string|max:255',
            '*.description' => 'required|string|max:255',
        ]);
        
        $result = [];
        foreach($dataArray as $data)
        {
            $city = City::create($data);
            $result[] = $city;
        }

        return response()->json($result, 201);
    }

    public function update(Request $request, City $city)
    {
        $city->update($request->all());

        return response()->json($city, 200);
    }

    public function delete(City $city)
    {
        $city->delete();
        $msg = 'City deleted successfully';
        return response()->json(['message' => $msg], 204);
    }
}
