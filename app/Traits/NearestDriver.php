<?php

namespace App\Traits;


trait NearestDriver{
    public function  NearestDriverByType($lat, $lon,$black_list = [],$type = '1' ,$radius = 100000){
        $data = \Modules\Drivers\Entities\Driver::selectRaw("*,
                         ( 6371000 * acos( cos( radians(?) ) *
                           cos( radians( CAST(JSON_EXTRACT(dr_drivers.location, '$.lat') as double) ) )
                           * cos( radians( CAST(JSON_EXTRACT(dr_drivers.location, '$.long') as double) ) - radians(?)
                           ) + sin( radians(?) ) *
                           sin( radians( CAST(JSON_EXTRACT(dr_drivers.location, '$.lat') as double) ) ) )
                         ) AS distance", [$lat, $lon, $lat])

            ->having("distance", "<", $radius)
            ->whereNotIn('id', $black_list)
            ->orderBy("distance",'asc')
            ->where('type_id', $type)
            ->offset(0)
            ->limit(20)
            ->first();
        return $data;
    }
//    public function  NearestVendorsByTypeWithoutRadius($lat, $lon,$type = '1'){
//        $data = \Modules\Vendors\Entities\Vendors::selectRaw("*,
//                         ( 6371000 * acos( cos( radians(?) ) *
//                           cos( radians( CAST(JSON_EXTRACT(vn_vendors.location, '$.lat') as double) ) )
//                           * cos( radians( CAST(JSON_EXTRACT(vn_vendors.location, '$.long') as double) ) - radians(?)
//                           ) + sin( radians(?) ) *
//                           sin( radians( CAST(JSON_EXTRACT(vn_vendors.location, '$.lat') as double) ) ) )
//                         ) AS distance", [$lat, $lon, $lat])
//
//            ->orderBy("distance",'asc')
//            ->where('type_id', $type)
//            ->offset(0)
//            ->limit(20)
//            ->get();
//        return $data;
//    }
//    public function  SpesficVendorsById($lat, $lon,$vendor_id){
//        $data = \Modules\Vendors\Entities\Vendors::selectRaw("*,
//                         ( 6371000 * acos( cos( radians(?) ) *
//                           cos( radians( CAST(JSON_EXTRACT(vn_vendors.location, '$.lat') as double) ) )
//                           * cos( radians( CAST(JSON_EXTRACT(vn_vendors.location, '$.long') as double) ) - radians(?)
//                           ) + sin( radians(?) ) *
//                           sin( radians( CAST(JSON_EXTRACT(vn_vendors.location, '$.lat') as double) ) ) )
//                         ) AS distance", [$lat, $lon, $lat])
//
//            ->orderBy("distance",'asc')
//            ->where('id', $vendor_id)
//            ->offset(0)
//            ->limit(20)
//            ->first();
//        return $data;
//    }
//
//    function distanceBetweenTwoPoints($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000){
//        $latFrom = deg2rad($latitudeFrom);
//        $lonFrom = deg2rad($longitudeFrom);
//        $latTo = deg2rad($latitudeTo);
//        $lonTo = deg2rad($longitudeTo);
//
//        $latDelta = $latTo - $latFrom;
//        $lonDelta = $lonTo - $lonFrom;
//
//        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
//                cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
//        return $angle * $earthRadius;
//    }
//
//    public function  SpesficVendorsByIdWithProductFeature($lat, $lon,$vendor_id,$feature){
//        $data = \Modules\Vendors\Entities\Vendors::with(['products','products.type'])->selectRaw("*,
//                         ( 6371000 * acos( cos( radians(?) ) *
//                           cos( radians( CAST(JSON_EXTRACT(vn_vendors.location, '$.lat') as double) ) )
//                           * cos( radians( CAST(JSON_EXTRACT(vn_vendors.location, '$.long') as double) ) - radians(?)
//                           ) + sin( radians(?) ) *
//                           sin( radians( CAST(JSON_EXTRACT(vn_vendors.location, '$.lat') as double) ) ) )
//                         ) AS distance", [$lat, $lon, $lat])
//
//            ->orderBy("distance",'asc')
//            ->where('id', $vendor_id)
//            ->offset(0)
//            ->limit(20)
//            ->first();
//        return $data;
//    }
//    public function  NearestAllVendors($lat, $lon, $radius = 100000){
//        $data = \Modules\Vendors\Entities\Vendors::selectRaw("*,
//                         ( 6371000 * acos( cos( radians(?) ) *
//                           cos( radians( CAST(JSON_EXTRACT(vn_vendors.location, '$.lat') as double) ) )
//                           * cos( radians( CAST(JSON_EXTRACT(vn_vendors.location, '$.long') as double) ) - radians(?)
//                           ) + sin( radians(?) ) *
//                           sin( radians( CAST(JSON_EXTRACT(vn_vendors.location, '$.lat') as double) ) ) )
//                         ) AS distance", [$lat, $lon, $lat])
//
//            ->having("distance", "<", $radius)
//            ->orderBy("distance",'asc')
//            ->offset(0)
//            ->limit(20)
//            ->get();
//        return $data;
//    }
//    public function spesficVendorDistance($lat, $lon, $vendor_id){
//        $data = \Modules\Vendors\Entities\Vendors::selectRaw("*,
//            ( 6371000 * acos( cos( radians(?) ) *
//              cos( radians( CAST(JSON_EXTRACT(vn_vendors.location, '$.lat') as double) ) )
//              * cos( radians( CAST(JSON_EXTRACT(vn_vendors.location, '$.long') as double) ) - radians(?)
//              ) + sin( radians(?) ) *
//              sin( radians( CAST(JSON_EXTRACT(vn_vendors.location, '$.lat') as double) ) ) )
//            ) AS distance", [$lat, $lon, $lat])
//
//            ->where('id', $vendor_id)
//            ->offset(0)
//            ->limit(20)
//            ->first();
//        return $data->distance;
//    }
}
