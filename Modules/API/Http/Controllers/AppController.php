<?php

namespace Modules\API\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AppController extends Controller{
    use \App\Traits\NearestDriver;
    use \App\Traits\NearestVendors;
    public function __construct(){
        $this->middleware('auth:api', [
            'except' => []
        ]);
    }
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
    public function profile(){
        $user = auth()->guard('api')->user();
        return $user;
    }

    public function updateProfile(Request $request){
        $user = auth()->guard('api')->user();
        $validator = \Validator::make($request->all(), [
            'email' => 'nullable|email:filter|unique:um_users,email,'.$user->id,
            'mobile_no' => 'nullable|min:8|max:13|unique:um_users,mobile_no,'.$user->id,
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => implode("\n", $validator->messages()->all())],403);
        } 
          
        $user = auth()->guard('api')->user();
        // $user->first_name = $request->first_name;
        $request->first_name ? $user->first_name = $request->first_name : '';
        $request->email ? $user->email = $request->email : '';
        $request->mobile_no ? $user->mobile_no = $request->mobile_no : '';
        $request->address ? $user->address = $request->address : '';
        $user->save();
        return response()->json(['message' => 'ok', 'data' => $user]);

    }
    public function changePassWhenLogin(Request $request){
        $user = auth()->guard('api')->user();
        $request->validate([
            'current_password' => 'required',
            'password' => 'required',
        ]);
        if($request->password != $request->confirm_password){
            return response()->json(['message' => 'The confirm password and password not match'],422);
        }
        if(!\Hash::check($request->current_password, $user->password )){
            return response()->json(['message' => 'The Current Password Not Correct'],422);
        }
        if(strlen($request->password) < 6){
            return response()->json([
                'message' => 'Password less than 6 characters'
            ],403);
       }
        
        $user = \Modules\Users\Entities\User::whereId($user->id)->first();
        $user->password = \Hash::make($request->password) ;
        $user->save();
        return response()->json([
            'data' => [
                'message' => 'ok',
            ]
        ]);
    }
    public function addImage(Request $request ){
        $user =auth()->guard('api')->user();
        $user = \Modules\Users\Entities\User::whereId($user->id)->first();
        if ($request->hasFile('file') && $request->file('file')->isValid()) {
            $extension = strtolower($request->file('file')->extension());
            $media_new_name = strtolower(md5(time())) . "." . $extension;
            $collection = "user-image";

            $user->addMediaFromRequest('file')
                ->usingFileName($media_new_name)
                ->usingName($request->file('file')->getClientOriginalName())
                ->toMediaCollection($collection);
                $user->save();
                return response()->json([
                    'data' => $user->personal_image_url
                ]);
        }
    }
}
