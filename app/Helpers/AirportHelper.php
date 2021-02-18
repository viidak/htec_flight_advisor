<?php

namespace App\Helpers;

use App\Models\Airport;
use App\Models\City;

class AirportHelper
{
    static public function import($name)
    {
        set_time_limit(0);
        $path = storage_path('app/imports/'.$name);
        $file = new \SplFileObject( $path );

        $validCities = City::select('name', 'country')->get();

        while( !$file -> eof() ) {
            $fileRow = $file->fgetcsv();

            foreach ( $fileRow as $key => $value ){
                $fileRow[$key] =  $value == '\N' ? 0 : $value;
            }
            
            if (Airport::where('airport_id', '=', $fileRow[0])->exists()) {
                continue;
            } elseif (isset($fileRow[2]) && $fileRow[2] !== '' && isset($fileRow[3]) && $fileRow[3] !== '') {
                if ($validCities->contains('name', $fileRow[2]) && $validCities->contains('country', $fileRow[3])) {
                    Airport::create([
                        'airport_id' => $fileRow[0],
                        'name' => $fileRow[1],
                        'city' => $fileRow[2],
                        'country' => $fileRow[3],
                        'iata' => $fileRow[4],
                        'icao' => $fileRow[5],
                        'latitude' => $fileRow[6],
                        'longitude' => $fileRow[7],
                        'altitude' => $fileRow[8],
                        'timezone' => $fileRow[9],
                        'DST' => $fileRow[10] === 0 ? 'U' : $fileRow[10],
                        'tz' => $fileRow[11],
                        'type' => $fileRow[12],
                        'source' => $fileRow[13],
                    ]);
                }
            }
        }

    }
}
