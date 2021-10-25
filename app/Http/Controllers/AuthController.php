<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Contest;
use App\Models\DetailContestCriteria;

use App\Models\Role;
use App\Models\ContestJury;

use Validator, Carbon, DB;
use Twilio\Rest\Client;
use App\Helper\ResponseHelper as JsonHelper;
use App\Models\DetailContestCriteriaContent;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login','changeUserName', 'register', 'otpVerify', 'resendOtp', 'changePhoneNumber', 'resetPasswordUser', 'resetPasswordUserVerification', 'resetPasswordUserChange']]);
    }

    /**
     * Get a JWT via given credentials.
     *  
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request){
        $res = new JsonHelper;

        if ($request->input('login_type') == 'user') {
            $validator = Validator::make($request->all(), [
                'phone' => 'required',
                'password' => 'required|string|min:6',
            ]);
    
            if ($validator->fails()) {
                return $res->responseGet(false, 400, null, $validator->errors()->first());
            }
    
            if (! $token = auth()->attempt($validator->validated())) {
                return $res->responseGet(false, 400, null, 'Wrong phone number and password!');
            }
            
            $dataUser = User::where('phone', $request->input('phone'))->first();
            if ($dataUser->role_id < 3 ) {
                return $res->responseGet(false, 400, null, 'This account is not able to join!');
            }

            $validateUser = User::where('phone', $request->input('phone'))->get();
            
            return $res->responseGet(true, 200, [
                'token' => $token
            ], 'Login success.');
            // return $this->createNewToken($token);
        } else if ($request->input('login_type') == 'admin') {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required|string|min:6',
            ]);
    
            if ($validator->fails()) {
                return $res->responseGet(false, 400, null, $validator->errors()->first());
            }
    
            if (! $token = auth()->attempt($validator->validated())) {
                return $res->responseGet(false, 400, null, 'Wrong email and password!');
            }
    
            $dataUser = User::where('email', $request->input('email'))->first();
            if ($dataUser->role_id !== 2) {
                return $res->responseGet(false, 400, null, 'This account is not able to join!');
            }

            return $res->responseGet(true, 200, [
                'token' => $token
            ], 'Login success.');
        } else if ($request->input('login_type') == 'jury') {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required|string|min:6',
            ]);

            if ($validator->fails()) {
                return $res->responseGet(false, 400, null, $validator->errors()->first());
            }
    
            if (! $token = auth()->attempt($validator->validated())) {
                return $res->responseGet(false, 400, null, 'Wrong email and password!');
            }

            $dataUser = User::where('email', $request->input('email'))->first();
            
            if ($dataUser->role_id !== 3) {
                return $res->responseGet(false, 400, null, 'This account is not able to join!');
            }

            if ($dataUser->verified_jury == 0) {
                return $res->responseGet(false, 400, null, 'This account is not able to join (not verified)!');
            }

            $isAvailRoom = DetailContestCriteriaContent::where('jury_code', $request->input('jury_code'))->get();

            $isRegistered = ContestJury::where('id_contest', $isAvailRoom[0]->id_contest)->get();

            if (count($isRegistered) == 0) {
                return $res->responseGet(false, 400, null, 'This account is not able to join cause this user is not registered on this contest!');
            }

            foreach ($isAvailRoom as $k => $v) {
                $nameContest = Contest::where('id', $v->id_contest)->first();
                $nameContestCriteria = DetailContestCriteria::where('id', $v->id_detail_contest_criteria)->first();
                
                $isAvailRoom[0]->contest_name = $nameContest->name;
                $isAvailRoom[0]->criteria_name = $nameContestCriteria->criteria_name;
                $isAvailRoom[0]->jury_name = $dataUser->name;
            }
            $isReadyToJugde = Contest::where('id', $v->id_contest)->first();

            if ((int)$isReadyToJugde->ready_to_jugde == 0) {
                return $res->responseGet(false, 400, null, 'Contest is not ready to judge (block is not set)!');
            }

            if (count($isAvailRoom) == 0) {
                return $res->responseGet(false, 400, null, 'Jury code not found!');
            }
    
            return $res->responseGet(true, 200, [
                'token' => $token,
                'room' => $isAvailRoom[0]
            ], 'Login success.');
        }
    }

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request) {
        
        $res = new JsonHelper;

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'password' => 'required|string|confirmed|min:6',
            'phone' => 'required|unique:users',
            'role_id' => 'required'
        ]);

        if($validator->fails()){
            return $res->responseGet(false, 400, null, $validator->errors()->first());
        }
        $otp = rand(100001, 999999);
        $otpResetPassword = rand(100001, 999999);

        $user = User::create(array_merge(
                    $validator->validated(),
                    [
                        'email' => $request->email,
                        'password' => bcrypt($request->password),
                        'password_show' => ($request->password),
                        'otp' => $otp,
                        'otp_reset_password' => $otpResetPassword,
                        'created_at' => Carbon\Carbon::now('Asia/Jakarta'),
                        'updated_at' => Carbon\Carbon::now('Asia/Jakarta')
                    ]
                ));
                // $this->sendWhatsappNotification($otp, $user->phone, $user->name);

        return $res->responseGet(true, 201, $user, 'User successfully registered');
    }

    public function resendOtp (Request $request) {
        $res = new JsonHelper;

        $user = User::where('id', auth()->user()->id)->first();
        // return $user;

        $this->sendWhatsappNotification($user->otp, $user->phone, $user->name);

        return $res->responseGet(true, 201, $user, 'Resend OTP success');
    }


    public function otpVerify(Request $request) {
        $res = new JsonHelper;

        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'otp' => 'required',
        ]);

        if($validator->fails()){
            return $res->responseGet(false, 400, null, $validator->errors()->first());
        }
        $isVerif = User::where('id', $request->input('id'))->get();
        
        if ($isVerif[0]->otp == $request->input('otp')) {
            $user = User::where('id', $request->input('id'))->update([
                'verified' => 1
            ]);
            return $res->responseGet(true, 200, null, 'Success verify otp');
        } else {
            return $res->responseGet(false, 400, null, 'Failed verify');
        }
    }

    public function changePhoneNumber (Request $request) {
        $res = new JsonHelper;

        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'phone' => 'required',
        ]);

        if($validator->fails()){
            return $res->responseGet(false, 400, null, $validator->errors()->first());
        }
        
        // if ($isVerif[0]->otp == $request->input('otp')) {
        $user = User::where('id', $request->input('id'))->update([
            'phone' => $request->input('phone')
        ]);
        return $res->responseGet(true, 200, null, 'Success update phone number');
        // } else {
            // return $res->responseGet(false, 400, null, 'Failed update phone number');
        // }
    }

    public function changeUserName (Request $request) {
        $res = new JsonHelper;

        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if($validator->fails()){
            return $res->responseGet(false, 400, null, $validator->errors()->first());
        }
        
        $user = User::where('id', auth()->user()->id)->update([
            'name' => $request->input('name')
        ]);
        return $res->responseGet(true, 200, null, 'Success update name');
    }

    public function resetPasswordUserChange (Request $request) {
        $res = new JsonHelper;

        $validator = Validator::make($request->all(), [
            'password' => 'required|string|confirmed|min:6',
        ]);

        if($validator->fails()){
            return $res->responseGet(false, 400, null, $validator->errors()->first());
        }
        
        // if ($isVerif[0]->otp == $request->input('otp')) {
        $user = User::where('phone', $request->input('phone'))->update([
            'password' => bcrypt($request->input('password'))
        ]);
        return $res->responseGet(true, 200, null, 'Success change password');
        // } else {
            // return $res->responseGet(false, 400, null, 'Failed update phone number');
        // }
    }

    public function resetPasswordUser (Request $request) {
        $res = new JsonHelper;

        $validator = Validator::make($request->all(), [
            'phone' => 'required',
        ]);

        if($validator->fails()){
            return $res->responseGet(false, 400, null, $validator->errors()->first());
        }

        $user = User::where('phone', $request->input('phone'))->get();
        // return $user;
        if (count($user) == 0) {
            return $res->responseGet(false, 400, null, 'Phone not in our databases');
        }
        $user = User::where('phone', $request->input('phone'))->first();

        $this->sendWhatsappNotificationResetPassword($user->otp_reset_password, $user->phone, $user->name);

        return $res->responseGet(true, 201, $user, 'Send OTP reset password success');
    }

    public function resetPasswordUserVerification (Request $request) {
        $res = new JsonHelper;

        $validator = Validator::make($request->all(), [
            'phone' => 'required',
        ]);

        if($validator->fails()){
            return $res->responseGet(false, 400, null, $validator->errors()->first());
        }

        // $user = User::where('id', $request->input('phone'))->first();
        // // return $user;
        
        $isVerif = User::where('phone', $request->input('phone'))->get();
        
        if ($isVerif[0]->otp_reset_password == $request->input('otp_reset_password')) {
            return $res->responseGet(true, 200, null, 'Success verify otp');
        } else {
            return $res->responseGet(false, 400, null, 'Failed verify because otp doesnt match');
        }

        return $res->responseGet(true, 201, $user, 'Send OTP reset password success');
    }

    private function sendWhatsappNotificationResetPassword(string $otpResetPass, string $recipient, string $name)
    {
        $url = "https://sendtalk-api.taptalk.io/api/v1/message/send_whatsapp";
        // $hour = \Carbon\Carbon::now('Asia/Jakarta')->format('H.i');
        $hour = \Carbon\Carbon::now('Asia/Jakarta')->addHours(3)->format('H.i');
        // return $hour;
        $header = array(
            'Content-Type:application/json',
            'API-key: 797ddd68908bae79b19e0909e2520a142d4961b2cc5ed1981a6aab29f593d8d8'
        );
        $data = json_encode(array(
            'phone' => $recipient,
            'messageType' => 'text',
            'body' => "Hai $name, Kode OTP reset kata sandi *GANTANGAN SULTAN* anda adalah *$otpResetPass* \nHarap masukkan kode sebelum pukul $hour WIB. Jangan beritahu kode ini kepada siapapun.\n\n *NOTE:* mohon tidak membalas pesan ini"
        ));

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        $result = curl_exec($ch);
        curl_close ($ch);
    }

    private function sendWhatsappNotification(string $otp, string $recipient, string $name)
    {
        $url = "https://sendtalk-api.taptalk.io/api/v1/message/send_whatsapp";
        // $hour = \Carbon\Carbon::now('Asia/Jakarta')->format('H.i');
        $hour = \Carbon\Carbon::now('Asia/Jakarta')->addHours(3)->format('H.i');
        // return $hour;
        $header = array(
            'Content-Type:application/json',
            'API-key: 797ddd68908bae79b19e0909e2520a142d4961b2cc5ed1981a6aab29f593d8d8'
        );
        $data = json_encode(array(
            'phone' => $recipient,
            'messageType' => 'text',
            'body' => "Hai $name, kode OTP pendaftaran akun *GANTANGAN SULTAN* anda adalah *$otp* \nHarap masukkan kode sebelum pukul $hour WIB. Jangan beritahu kode ini kepada siapapun.\n\n *NOTE:* mohon tidak membalas pesan ini"
        ));

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        $result = curl_exec($ch);
        curl_close ($ch);

        // return $result;

        // $sid    = "ACc62a9b5353ec439ad6b20408cb1367f5"; 
        // $token  = "b33ef41445fb2b3db0c793f025fdca9b"; 
        // $twilio = new Client($sid, $token); 
        
        // $message = $twilio->messages 
        //                 ->create("whatsapp:$recipient", // to 
        //                         array( 
        //                             "from" => "whatsapp:+14155238886",       
        //                             "body" => "Hi $name,Your registration pin code is $otp" 
        //                         ) 
        //                 ); 
        // return 'ok';
        // $twilio_whatsapp_number = "+15164609558";
        // $account_sid = "ACc62a9b5353ec439ad6b20408cb1367f5";
        // $auth_token = "b33ef41445fb2b3db0c793f025fdca9b";

        // // $twilio_whatsapp_number = getenv("TWILIO_WHATSAPP_NUMBER");
        // // $account_sid = getenv("TWILIO_ACCOUNT_SID");
        // // $auth_token = getenv("TWILIO_AUTH_TOKEN");

        // $client = new Client($account_sid, $auth_token);
        // $message = "Your registration pin code is $otp";
        // return $client->messages->create("whatsapp:$recipient", array('from' => "whatsapp:$twilio_whatsapp_number", 'body' => $message));
    }

    

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout() {
        $res = new JsonHelper;

        auth()->logout();
        return $res->responseGet(true, 200, null, 'User successfully signed out');
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh() {
        return $this->createNewToken(auth()->refresh());
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile() {
        $res = new JsonHelper;
        $user = User::where('id', auth()->user()->id)->first();

        $roleName = Role::where('id', $user->role_id)->first();

        $user->role_name = $roleName->name;
        return $res->responseGet(true, 200, $user, '');
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token){
        $res = new JsonHelper;

        return $res->responseGet(true, 200, [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ], '');
    }
    

}