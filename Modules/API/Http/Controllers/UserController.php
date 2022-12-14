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
            'confirm_password' => 'required|min:6|same:password',
            'first_name' => 'required|min:3',
            'mobile_no' => 'required|min:8|max:13',
            'location' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'code' => 200,'message' => implode("\n", $validator->messages()->all())]);
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
                $code->code = $verificationCode;
                $code->save();
            }

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json(['message' => $e->getMessage()], 403);
        }
        return response()->json(['status' => true, 'code' => 200, 'message' => 'please confirm mobile number', 'user' => $user,]);
          
    }

    public function login(Request $request){
        $email = $request->email;
        $password = $request->password;

        $validator = \Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'code' => 200,
                'message' => implode("\n", $validator->messages()->all())]);
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
                return response()->json(['status' => true, 'code' => 210, 'message' => $message]);
            }
            else {
                $user['access_token'] = $user->createToken('mobile_no')->accessToken;
                return response()->json(['status' => true, 'code' => 200, 'user' => $user]);
            }
        } else {

            $EmailData = \Modules\Users\Entities\User::query()->where(['email' => $email])->first();
            if ($EmailData) {
                $message = __('wrong password');

                return response()->json(['status' => false, 'code' => 200, 'message' => $message]);

            } else {
                $message = __('wrong email');

                return response()->json(['status' => false, 'code' => 200, 'message' => $message]);
            }
        }
    }
    public function sendCodeToApi(Request $request){
        $validator = \Validator::make($request->all(), [
            'code' => 'required|min:4',
            'mobile_no' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'code' => 200,
                'message' => implode("\n", $validator->messages()->all())]);
        }
        $code = convertAr2En($request->code);
        $user = \Modules\Users\Entities\User::where('mobile_no', $request->mobile_no)->first();
        if(!$user){
            return response()->json(['status' => false, 'code' => 200, 'message' => 'Not Exsist']);
        }
        $validationCode = new \Modules\Users\Entities\VerificationCode;
        $validationCode->mobile_no = $request->mobile_no;
        $validationCode->code = $request->code;
        $validationCode->save();
        return response()->json(['status' => true, 'code' => 200, 'message' => 'ok']);
       
    }
    public function verifyCode(Request $request){
        $validator = \Validator::make($request->all(), [
            'mobile_no' => 'required',
            'code' => 'required|min:4',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'code' => 200,
                'message' => implode("\n", $validator->messages()->all())]);
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
                $massege = __('ok');
                return response()->json(['status' => true, 'code' => 200, 'message' => $massege, 'user' => $user]);
                }
            } else {
                $massege = __('incorrect code');
                return response()->json(['status' => false, 'code' => 200, 'message' => $massege]);
            }

        } else {
            $massege = __('incorrect code');
            return response()->json(['status' => false, 'code' => 200, 'message' => $massege]);

        }
    }

    public function forgotPassword(Request $request){
        $validator = \Validator::make($request->all(), [
            'mobile_no' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'code' => 200,
                'message' => implode("\n", $validator->messages()->all())]);
        }
        $user = \Modules\Users\Entities\User::where('mobile_no', $request->mobile_no)->first();
        if (!$user) {
            $message = 'The mobile number not found';
            return response()->json(['status' => false, 'code' => 200, 'message' => $message]);
        }
        // $token = $this->broker()->createToken($user);
        // $url = url('/password/reset/' . $token);
        // $user->notify(new ResetPassword($token));
        $message = __('reset Password');
        return response()->json(['status' => true, 'code' => 200, 'message' => $message]);
    }

    public function changePassword(Request $request){
        $rules = [
            'old_password' => 'required|min:6',
            'password' => 'required|min:6',
            'confirm_password' => 'required|min:6|same:password',
        ];
        $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'code' => 200,
                'message' => implode("\n", $validator->messages()->all())]);
        }
        $user = auth('api')->user();
        if (!Hash::check($request->old_password, $user->password)) {
            $message = __('old_password'); //wrong old
            return response()->json(['status' => false, 'code' => 200, 'message' => $message,
                'validator' => $validator]);
        }

        $user->password = Hash::make($request->password);

        if ($user->save()) {
            $user->refresh();
            $message = __('ok');
            return response()->json(['status' => true, 'code' => 200, 'message' => $message]);
        }
        $message = __('whoops');
        return response()->json(['status' => false, 'code' => 200, 'message' => $message]);
    }

    public function logout(Request $request){
        $user_id = auth('api')->id();
        Token::where('fcm_token', $request->fcmToken)->delete();
        if (auth('api')->user()->token()->revoke()) {
            $message = 'logged out successfully';
            return response()->json(['status' => true, 'code' => 200,
                'message' => $message]);
        } else {
            $message = 'logged out successfully';
            return response()->json(['status' => true, 'code' => 202,
                'message' => $message]);
        }
    }
}
