<?php

namespace App\Helpers;

use App\Models\Route;
use App\Http\Controllers\AirportController;

class RouteHelper
{
    static public function import($name)
    {
        set_time_limit(0);
        $path = storage_path('app/imports/'.$name);
        $file = new \SplFileObject( $path );

        while( !$file -> eof() ) {
            $fileRow = $file->fgetcsv();

            foreach ( $fileRow as $key => $value ){
                $fileRow[$key] =  $value == '\N' ? 0 : $value;
            }

            $validAirportIdList = AirportController::getIdList();

            if ((isset($fileRow[3]) && in_array($fileRow[3], $validAirportIdList)) 
                && (isset($fileRow[5]) && in_array($fileRow[5], $validAirportIdList))) 
            {  //  Check if airports exist
                Route::create([
                    'airline' => $fileRow[0],
                    'airline_id' => $fileRow[1],
                    'source_airport' => $fileRow[2],
                    'source_airport_id' => $fileRow[3],
                    'destination_airport' => $fileRow[4],
                    'destination_airport_id' => $fileRow[5],
                    'codeshare' => $fileRow[6],
                    'stops' => $fileRow[7],
                    'equipment' => $fileRow[8],
                    'price' => $fileRow[9],
                ]);
            }
        }
    }

    /**
     * Calculates the great-circle distance between two points, with
     * the Vincenty formula.
     * @param float $latitudeFrom Latitude of start point in [deg decimal]
     * @param float $longitudeFrom Longitude of start point in [deg decimal]
     * @param float $latitudeTo Latitude of target point in [deg decimal]
     * @param float $longitudeTo Longitude of target point in [deg decimal]
     * @param float $earthRadius Mean earth radius in [km]
     * @return float Distance between points in [km] (same as earthRadius)
     */
    public static function vincentyGreatCircleDistance(
    $latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371)
    {
        // convert from degrees to radians
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);

        $lonDelta = $lonTo - $lonFrom;
        $a = pow(cos($latTo) * sin($lonDelta), 2) +
            pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
        $b = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);

        $angle = atan2(sqrt($a), $b);
        return $angle * $earthRadius;
    }
}
