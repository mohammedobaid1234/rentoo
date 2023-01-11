<?php

namespace Modules\API\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class UserController extends Controller{
    public function signUp(Request $request){
        
        $validator = \Validator::make($request->all(), [
            'email' => 'required|email:filter|unique:um_users',
            'password' => 'required|min:6',
            // 'confirm_password' => 'required|min:6|same:password',
            'first_name' => 'required|min:3',
            'mobile_no' => 'required|min:8|max:13|unique:um_users',
            // 'location' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([ 'message' => implode("\n", $validator->messages()->all())],403);
        }
        \DB::beginTransaction();
        try {
            $user =  new \Modules\Users\Entities\User();
            $user->first_name = $request->first_name;
            $user->email = $request->email;
            $user->mobile_no = $request->mobile_no;
            $user->location = $request->location;
            $user->password = \Hash::make($request->password);
            $done = $user->save();
            if($done){
                $verificationCode = rand(1000, 9999);
                $code = new \Modules\Users\Entities\VerificationCode();
                $code->email = $user->email;
                $code->mobile_no = $user->mobile_no;
                $code->code = 1111;
                $code->save();
            }
            // $user->notify(new \Modules\Users\Notifications\SendVerificationCode($code->code));
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json(['message' => $e->getMessage()], 403);
        }
        return response()->json(['message' =>'please confirm mobile number', 'user' => $user,]);
          
    }

    public function login(Request $request){
        $email = $request->email;
        $password = $request->password;

        $validator = \Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => implode("\n", $validator->messages()->all())],403);
        }

        if (filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            $conditions = ['email' => $request->email, 'password' => $request->password];
        } else {
            $conditions = ['mobile_no' => $request->email, 'password' => $request->password];
        }

        if (\Auth::once($conditions)) {
            $user = \Auth::user();
            if ($user->verified == 0) {
                $code = new \Modules\Users\Entities\VerificationCode();
                $code->mobile_no = $user->mobile_no;
                $code->email = $user->email;
                $code->code = 1111;
                $code->save();
                $message = 'Must Verified Mobile Number';
                return response()->json([  'message' => $message]);
            }
            else {
                $user['access_token'] = $user->createToken('mobile_no')->accessToken;
                return response()->json([  'user' => $user]);
            }
        } else {

            $EmailData = \Modules\Users\Entities\User::query()->where(['email' => $email])->first();
            if ($EmailData) {
                $message = __('wrong password');

                return response()->json([ 'message' => $message],403);

            } else {
                $message = __('wrong email');

                return response()->json([ 'message' => $message],403);
            }
        }
    }
   
    // public function sendCodeToApi(Request $request){
    //     $validator = \Validator::make($request->all(), [
    //         'code' => 'required|min:4',
    //         'mobile_no' => 'required',
    //     ]);
    //     if ($validator->fails()) {
    //         return response()->json([
    //             'me,403ssage' => implode("\n", $validator->messages()->all())]);
    //     }
    //     $code = convertAr2En($request->code);
    //     $user = \Modules\Users\Entities\User::where('mobile_no', $request->mobile_no)->first();
    //     if(!$user){
    //         return response()->json([ 'message' => 'Not Exsi,403st']);
    //     }
    //     $validationCode = new \Modules\Users\Entities\VerificationCode;
    //     $validationCode->mobile_no = $request->mobile_no;
    //     $validationCode->code = $request->code;
    //     $validationCode->save();
    //     return response()->json([  'message' => 'ok']);
       
    // }
    public function verifyCode(Request $request){
        $validator = \Validator::make($request->all(), [
            'mobile_no' => 'required',
            'code' => 'required|min:4',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => implode("\n", $validator->messages()->all())],403);
        } 

        $code = convertAr2En($request->code);
        $item = \Modules\Users\Entities\VerificationCode::where('mobile_no', $request->mobile_no)
        ->where('code', $code)
        ->orderBy('created_at', 'desc')
        ->first();
        if ($item) {
            if ($code == $item->code) {
                $item->delete();
                $user = \Modules\Users\Entities\User::where('mobile_no', $request->mobile_no)->first();
                if ($user) {
                    $user->verified = 1;
                    $user->status = 'active';
                    $user->save();
                    \Auth::login($user);
                     if ($request->has('fcm_token')) {
                        Token::updateOrCreate(['device_type' => $request->get('device_type'), 'fcm_token' => $request->get('fcm_token'), 'lang' => app()->getLocale()]
                        , ['user_id' => $user->id]);
                    }
                    $user['access_token'] = $user->createToken('mobile_no')->accessToken;
                $massege = __('Verified Successfully');
                return response()->json([  'message' => $massege, 'user' => $user]);
                }
            } else {
                $massege = __('incorrect code');
                return response()->json([ 'message' => $massege],403);
            }

        } else {
            $massege = __('incorrect code');
            return response()->json([ 'message' => $massege],403);

        }
    }

    public function forgotPassword(Request $request){
        $validator = \Validator::make($request->all(), [
            'mobile_no' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => implode("\n", $validator->messages()->all())],403);
        }
        $user = \Modules\Users\Entities\User::where('mobile_no', $request->mobile_no)->first();
        if (!$user) {
            $message = 'The mobile number not found';
            return response()->json([ 'message' => $message],403);
        }
        // $token = $this->broker()->createToken($user);
        // $url = url('/password/reset/' . $token);
        // $user->notify(new ResetPassword($token));
        $message = __('reset Password');
        return response()->json([  'message' => $message]);
    }


    public function changePasswordWhenForging(Request $request){
        $rules = [
            'password' => 'required|min:6',
            'confirm_password' => 'required|min:6|same:password',
            'mobile_no' => 'required'
        ];
        $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'message' => implode("\n", $validator->messages()->all())],403);
        }
        $user = \Modules\Users\Entities\User::where('mobile_no', $request->mobile_no)->first();

        $user->password = \Hash::make($request->password);

        if ($user->save()) {
            $user->refresh();
            $message = __('Change Password Successfully');
            return response()->json([  'message' => $message]);
        }
        $message = __('whoops');
        return response()->json([ 'message' => $message],403);
    }

    public function logout(Request $request){
        $user_id = auth('api')->id();
        // Token::where('fcm_token', $request->fcmToken)->delete();
        if (auth('api')->user()->token()->revoke()) {
            $message = 'logged out successfully';
            return response()->json([ 
                'message' => $message]);
        } else {
            $message = 'logged out successfully';
            return response()->json([ 'message' => $message]);
        }
    }
}
