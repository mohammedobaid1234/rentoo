<?php

use Illuminate\Support\Facades\Http;

function getLocationFromLatAndLong($lat  ,  $long , $lang = 'en'){
    $location= $lat. '%2C' . $long;
    $response = Http::get('https://api.opencagedata.com/geocode/v1/json', [
        'q' => $location,
        'key' => config('services.opencage')['key'],
        'language' => $lang,
        'pretty' => '1'
    ]);
    $result =  json_decode($response) ??  'No Exsit Format Address';
    return $result->results ?  $result->results[0]->formatted :'No Exsit Format Address' ;
    // return $result->results[0]->formatted;
}