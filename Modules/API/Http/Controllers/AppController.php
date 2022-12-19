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
    public function homePage(){
        $user = auth()->guard('api')->user();
        $typeOfVendor = \Modules\Vendors\Entities\TypeOFVendor::get();
        $typeOfVendorCollection = collect([]);
        foreach ($typeOfVendor as $item) {
            $data['id'] = $item->id;
            $data['name'] = $item->getTranslations('name')['ar'];
            $typeOfVendorCollection->add($data);
        }
        $userLocation =json_decode($user->location);
        $offers = \Modules\Vendors\Entities\Offer::limit(4)->get();
        return $offers;
        $restaurants =  $this->NearestVendorsByType($userLocation->lat, $userLocation->long,'1',$user->province_id);

    }
}
