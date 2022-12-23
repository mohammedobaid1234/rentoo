<?php

namespace Modules\API\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AppController extends Controller{
    use \App\Traits\NearestDriver;
    use \App\Traits\NearestVendors;
    public function getTags(){
        $tags = \Modules\Products\Entities\Tag::get();
        $tagsCollection = collect([]);
        foreach ($tags as $tag) {
            $data['id'] = $tag->id;
            $data['name'] = $tag->getTranslations('name')['ar'];
            $tagsCollection->add($data);
        }
        return  response()->json([ 'data' => $tagsCollection]);
    }

    public function addTagsToUser(Request $request){
        $validator = \Validator::make($request->all(), [
            'tags' => 'required|array',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => implode("\n", $validator->messages()->all())],403);
        }
        $user = auth()->guard('api')->user();
        if(count($request->tags) == 0){
            return response()->json(['message' => 'should add tags'], 403);
        }
        foreach ($request->tags as $tag) {
            $userTag =new  \Modules\Products\Entities\UserTag;
            $userTag->tag_id = $tag;
            $userTag->user_id = $user->id;
            $userTag->save();
        }
        return response()->json(['message' => 'ok']);

    }
    public function homePage(Request $request){
        $user = auth()->guard('api')->user();
        $typeOfVendor = \Modules\Vendors\Entities\TypeOFVendor::get();
        $typeOfVendorCollection = collect([]);
        foreach ($typeOfVendor as $item) {
            $data['id'] = $item->id;
            $data['name'] = $item->getTranslations('name')['ar'];
            $typeOfVendorCollection->add($data);
        }
        $userLocation =json_decode($user->location);
        $offersCollection = collect([]);
        $offers = \Modules\Vendors\Entities\Offer::limit(4)->get();
        foreach ($offers as $item) {
            $data['id'] = $item->id;
            $data['name'] = $item->getTranslations('name')['ar'];
            $data['image_url'] = $item->image_url;
            $offersCollection->add($data);
        }
        $vendors =  $this->NearestVendorsByType($userLocation->lat, $userLocation->long,$request->type_id??'1');
        $nearestProductsCollections = collect([]);
        foreach ($vendors as $vendor) {
            // dd(json_decode($vendor->location)->lng);
            foreach ($vendor->products as $item) {
                $data['id'] = $item->id;
                $data['name'] = $item->getTranslations('name')['ar'];
                $data['image_url'] = $item->image_url;
                $data['vendor_name'] =  $vendor->company_name;
                $data['address'] = getLocationFromLatAndLong(json_decode($vendor->location)->lat,json_decode($vendor->location)->lng );
                $nearestProductsCollections->add($data);
            }
        }
        $bestProductsForYou = collect([]);
        $userTags =  $user->tags->pluck('tag_id');
      
        $products = \Modules\Products\Entities\Product::with('attributes.attribute')->whereHas('tags', function($q)use($userTags){
            $q->whereIn('tag_id',$userTags);
        })->get();
        $attributeCollection = collect([]);
        foreach ($products as $product) {
            $data['id'] = $product->id;
            $data['name'] = $item->getTranslations('name')['ar'];
            $data['image_url'] = $item->image_url;
            $data['vendor_name'] =  $vendor->company_name;
            foreach ($product->attributes as $attribute) {
                $attributeData['id'] = $attribute->attribute->id;
                $attributeData['name'] = $attribute->attribute->getTranslations('name')['ar'];
                $attributeData['value'] = $attribute->value;
                $attributeCollection->add($attributeData);
            }
            $data['attributes'] = $attributeCollection;
            $bestProductsForYou->add($data);
        }
        return response()->json([
            'data' => [
                'offers' => $offersCollection,
                'nearestProductsCollections' => $nearestProductsCollections,
                'bestProductsForYou' => $bestProductsForYou
            ]
        ]);

    }
}
