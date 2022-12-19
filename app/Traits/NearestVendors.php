<?php
  
namespace App\Traits;
  
  
trait NearestVendors{
    public function  NearestVendorsByTypeWithFeature($lat, $lon, $feature = '1'  ,$type = '1', $radius = 100000){
        $data = \Modules\Vendors\Entities\Vendors::selectRaw("*,
                         ( 6371000 * acos( cos( radians(?) ) *
                           cos( radians( CAST(JSON_EXTRACT(vn_vendors.location, '$.lat') as double) ) )
                           * cos( radians( CAST(JSON_EXTRACT(vn_vendors.location, '$.long') as double) ) - radians(?)
                           ) + sin( radians(?) ) *
                           sin( radians( CAST(JSON_EXTRACT(vn_vendors.location, '$.lat') as double) ) ) )
                         ) AS distance", [$lat, $lon, $lat],
                        
                         )

            ->orderBy("distance",'asc')
            ->where('type_id', $type)
            ->offset(0)
            ->limit(20)
            ->whereHas('type', function ($q) use($feature){
                $q->where('feature_type', $feature);
            })
            ->get();
        $finalData = collect([]);
        foreach ($data as $d) {
          $finalData->push($d);
          // if($d->maximum_distance > $d->distance){
          // }
        }
        return $finalData;
    }
    public function  NearestVendorsByType($lat, $lon,$type = '1',$province_id, $radius = 100000){
        $data = \Modules\Vendors\Entities\Vendors::selectRaw("*,
                         ( 6371000 * acos( cos( radians(?) ) *
                           cos( radians( CAST(JSON_EXTRACT(vn_vendors.location, '$.lat') as double) ) )
                           * cos( radians( CAST(JSON_EXTRACT(vn_vendors.location, '$.long') as double) ) - radians(?)
                           ) + sin( radians(?) ) *
                           sin( radians( CAST(JSON_EXTRACT(vn_vendors.location, '$.lat') as double) ) ) )
                         ) AS distance", [$lat, $lon, $lat])

            // ->having("distance", "<", $radius)
            ->orderBy("distance",'asc')
            ->where('type_id', $type)
            // ->whereHas('user', function($q)use($province_id){
            //   $q->where('um_users.province_id', $province_id);
            // })
            ->offset(0)
            ->limit(20)
            ->get();
            // dd($data);
          $finalData = collect([]);
          foreach ($data as $d) {
            $finalData->push($d);
            if($d->maximum_distance > $d->distance){
            }
          }
          return $finalData;
    }
    public function  NearestVendorsByTypeWithoutRadius($lat, $lon,$type = '1'){
        $data = \Modules\Vendors\Entities\Vendors::selectRaw("*,
                         ( 6371000 * acos( cos( radians(?) ) *
                           cos( radians( CAST(JSON_EXTRACT(vn_vendors.location, '$.lat') as double) ) )
                           * cos( radians( CAST(JSON_EXTRACT(vn_vendors.location, '$.long') as double) ) - radians(?)
                           ) + sin( radians(?) ) *
                           sin( radians( CAST(JSON_EXTRACT(vn_vendors.location, '$.lat') as double) ) ) )
                         ) AS distance", [$lat, $lon, $lat])

            ->orderBy("distance",'asc')
            ->where('type_id', $type)
            ->offset(0)
            ->limit(20)
            ->get();
          $finalData = collect([]);
          foreach ($data as $d) {
            $finalData->push($d);
            if($d->maximum_distance > $d->distance){
            }
          }
          return $finalData;
    }
    public function  SpesficVendorsById($lat, $lon,$vendor_id){
        $data = \Modules\Vendors\Entities\Vendors::selectRaw("*,
                         ( 6371000 * acos( cos( radians(?) ) *
                           cos( radians( CAST(JSON_EXTRACT(vn_vendors.location, '$.lat') as double) ) )
                           * cos( radians( CAST(JSON_EXTRACT(vn_vendors.location, '$.long') as double) ) - radians(?)
                           ) + sin( radians(?) ) *
                           sin( radians( CAST(JSON_EXTRACT(vn_vendors.location, '$.lat') as double) ) ) )
                         ) AS distance", [$lat, $lon, $lat])

            ->orderBy("distance",'asc')
            ->where('id', $vendor_id)
            ->offset(0)
            ->limit(20)
            ->first();
            return $data;
          if($data->maximum_distance > $data->distance){
          }
    }
   
    function distanceBetweenTwoPoints($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000){
      $latFrom = deg2rad($latitudeFrom);
      $lonFrom = deg2rad($longitudeFrom);
      $latTo = deg2rad($latitudeTo);
      $lonTo = deg2rad($longitudeTo);
    
      $latDelta = $latTo - $latFrom;
      $lonDelta = $lonTo - $lonFrom;
    
      $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
        cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
      return $angle * $earthRadius;
    }

    public function  SpesficVendorsByIdWithProductFeature($lat, $lon,$vendor_id,$feature){
        $data = \Modules\Vendors\Entities\Vendors::with(['products','products.type'])->selectRaw("*,
                         ( 6371000 * acos( cos( radians(?) ) *
                           cos( radians( CAST(JSON_EXTRACT(vn_vendors.location, '$.lat') as double) ) )
                           * cos( radians( CAST(JSON_EXTRACT(vn_vendors.location, '$.long') as double) ) - radians(?)
                           ) + sin( radians(?) ) *
                           sin( radians( CAST(JSON_EXTRACT(vn_vendors.location, '$.lat') as double) ) ) )
                         ) AS distance", [$lat, $lon, $lat])

            ->orderBy("distance",'asc')
            ->where('id', $vendor_id)
            ->offset(0)
            ->limit(20)
            ->first();
            return $data;
            if($data->maximum_distance > $data->distance){
            }
            
    }
    public function  NearestAllVendors($lat, $lon, $radius = 100000){
        $data = \Modules\Vendors\Entities\Vendors::selectRaw("*,
                         ( 6371000 * acos( cos( radians(?) ) *
                           cos( radians( CAST(JSON_EXTRACT(vn_vendors.location, '$.lat') as double) ) )
                           * cos( radians( CAST(JSON_EXTRACT(vn_vendors.location, '$.long') as double) ) - radians(?)
                           ) + sin( radians(?) ) *
                           sin( radians( CAST(JSON_EXTRACT(vn_vendors.location, '$.lat') as double) ) ) )
                         ) AS distance", [$lat, $lon, $lat])

            // ->having("distance", "<", $radius)
            ->orderBy("distance",'asc')
            ->offset(0)
            ->limit(20)
            ->get();
            $finalData = collect([]);
          foreach ($data as $d) {
            $finalData->push($d);
            if($d->maximum_distance > $d->distance){
            }
          }
          return $finalData;
    }
    public function spesficVendorDistance($lat, $lon, $vendor_id){
            $data = \Modules\Vendors\Entities\Vendors::selectRaw("*,
            ( 6371000 * acos( cos( radians(?) ) *
              cos( radians( CAST(JSON_EXTRACT(vn_vendors.location, '$.lat') as double) ) )
              * cos( radians( CAST(JSON_EXTRACT(vn_vendors.location, '$.long') as double) ) - radians(?)
              ) + sin( radians(?) ) *
              sin( radians( CAST(JSON_EXTRACT(vn_vendors.location, '$.lat') as double) ) ) )
            ) AS distance", [$lat, $lon, $lat])

      ->where('id', $vendor_id)
      ->offset(0)
      ->limit(20)
      ->first();
      $finalData = collect([]);
      return $data->distance;
      if($data->maximum_distance > $data->distance){
      }
    }
}