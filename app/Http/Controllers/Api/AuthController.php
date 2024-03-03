<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Models\User;
use App\Models\GeneralSetting;
use App\Models\UserSubscription;
use File;
use Carbon\Carbon;

class AuthController extends Controller
{	

	//Check email
	public function checkEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users',
        ]);
   
        if($validator->fails()){
            $messages = $validator->errors()->messages();
			
			foreach ($messages as $key => $value)
			{
				$error[] = $value[0];
			}
			
            return response()->json($error, 406); 
        }
		
        if(app()->environment('production'))
		{
			$otp = rand(1111, 9999);
		}
		else
		{
			$otp = '1234';
		}

		$data['email'] = $request->email;
		$data['otp'] = $otp;
		
        return response()->json(['message' => 'OTP Send Successfully','data' => $data], 200);
    }

	//Register with email
    public function registerEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ]);
   
        if($validator->fails()){
            $messages = $validator->errors()->messages();
			
			foreach ($messages as $key => $value)
			{
				$error[] = $value[0];
			}
			
            return response()->json($error, 406); 
        }
		
		$get_general_setting =  GeneralSetting::where('status', 1)->first();
		$subscription_end_date = Carbon::now()->addDays($get_general_setting->trial_period_days);
		
		$user = new User();
		$user->name = $request->name;
		$user->email = $request->email;
		$user->password = bcrypt($request->password);
		$user->device_token = $request->device_token;
		$user->device_os = $request->device_os;
		$user->device_os_version = $request->device_os_version;
		$user->device_model = $request->device_model;
		$user->ip_address = $request->ip_address;
		$user->last_login_at = date("Y-m-d H:i:s");
		$user->subscription_start_date = date("Y-m-d H:i:s");
		$user->subscription_end_date = $subscription_end_date->toDateTimeString();
		$user->save();
		
        $token = $user->createToken('Token')->accessToken;
		
		//User subscription
		$user_subscription = new UserSubscription();
		$user_subscription->user_id = $user->id;
		$user_subscription->subscription_start_date = $user->subscription_start_date;
		$user_subscription->subscription_end_date = $user->subscription_start_date;
		$user_subscription->save();
		
        return response()->json(['message' => 'User Create Successfully','data' => $user,'token' => $token], 200);
    }
	
	//Login with email
	public function loginEmail(Request $request)
	{
		$validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);
		
		if ($validator->fails())
		{
            $messages = $validator->errors()->messages();
			
			foreach ($messages as $key => $value)
			{
				$error[] = $value[0];
			}
			
            return response()->json($error, 406);
        }
		else
		{
			if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) 
			{
				$token = auth()->user()->createToken('Token')->accessToken;
				
				$user = User::find(Auth::guard('web')->user()->id);
				$user->device_token = $request->device_token;
				$user->device_os = $request->device_os;
				$user->device_os_version = $request->device_os_version;
				$user->device_model = $request->device_model;
				$user->last_login_at = date("Y-m-d H:i:s");
				$user->save();
				
				return response()->json(['message' => 'Login Successfully','data' => $user,'token' => $token], 200);
			}
			else
			{
				return response()->json(['Credentials Does not Match !'], 406);
			}
		}
	}

    ////Resend otp email
    public function resendOtpEmail(Request $request)
    {
		$validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);
   
        if($validator->fails()){
            $messages = $validator->errors()->messages();
			
			foreach ($messages as $key => $value)
			{
				$error[] = $value[0];
			}
			
            return response()->json($error, 406); 
        }
		
        if(app()->environment('production'))
		{
			$otp = rand(1111, 9999);
		}
		else
		{
			$otp = '1234';
		}

		$data['email'] = $request->email;
		$data['otp'] = $otp;
		
        return response()->json(['message' => 'OTP Send Successfully','data' => $data], 200);
    }

    //Check phone
	public function checkPhone(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'country_code' => 'required',
            'phone_number' => 'required|numeric|unique:users',
        ]);
   
        if($validator->fails()){
            $messages = $validator->errors()->messages();
			
			foreach ($messages as $key => $value)
			{
				$error[] = $value[0];
			}
			
            return response()->json($error, 406); 
        }
		
        if(app()->environment('production'))
		{
			$otp = rand(1111, 9999);
		}
		else
		{
			$otp = '1234';
		}

		$data['phone_number'] = $request->phone_number;
		$data['otp'] = $otp;

        return response()->json(['message' => 'OTP Send Successfully','data' => $data], 200);
    }

	//Register with phone
    public function registerPhone(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'country_code' => 'required',
            'phone_number' => 'required|digits:10|numeric|unique:users',
            'password' => 'required',
        ]);
   
        if($validator->fails()){
            $messages = $validator->errors()->messages();
			
			foreach ($messages as $key => $value)
			{
				$error[] = $value[0];
			}
			
            return response()->json($error, 406); 
        }
		
		$get_general_setting =  GeneralSetting::where('status', 1)->first();
		$subscription_end_date = Carbon::now()->addDays($get_general_setting->trial_period_days);
		
		$user = new User();
		$user->name = $request->name;
		$user->country_code = $request->country_code;
		$user->phone_number = $request->phone_number;
		$user->password = bcrypt($request->password);
		$user->device_token = $request->device_token;
		$user->device_os = $request->device_os;
		$user->device_os_version = $request->device_os_version;
		$user->device_model = $request->device_model;
		$user->ip_address = $request->ip_address;
		$user->subscription_start_date = date("Y-m-d H:i:s");
		$user->subscription_end_date = $subscription_end_date->toDateTimeString();
		$user->last_login_at = date("Y-m-d H:i:s");
		$user->save();

        $token = $user->createToken('Token')->accessToken;
 
		//User subscription
		$user_subscription = new UserSubscription();
		$user_subscription->user_id = $user->id;
		$user_subscription->subscription_start_date = $user->subscription_start_date;
		$user_subscription->subscription_end_date = $user->subscription_start_date;
		$user_subscription->save();
		
        return response()->json(['message' => 'User Create Successfully','data' => $user,'token' => $token], 200);
    }

    //Login with Phone
	public function loginPhone(Request $request)
	{
		$validator = Validator::make($request->all(), [
            'country_code' => 'required',
            'phone_number' => 'required',
            'password' => 'required',
        ]);
		
		if ($validator->fails())
		{
            $messages = $validator->errors()->messages();
			
			foreach ($messages as $key => $value)
			{
				$error[] = $value[0];
			}
			
            return response()->json($error, 406);
        }
		else
		{
			if (Auth::attempt(['country_code' => $request->country_code, 'phone_number' => $request->phone_number, 'password' => $request->password])) 
			{
				$token = auth()->user()->createToken('Token')->accessToken;
				
				$user = User::find(Auth::guard('web')->user()->id);
				$user->device_token = $request->device_token;
				$user->device_os = $request->device_os;
				$user->device_os_version = $request->device_os_version;
				$user->device_model = $request->device_model;
				$user->last_login_at = date("Y-m-d H:i:s");
				$user->save();
				
				return response()->json(['message' => 'Login Successfully','data' => $user,'token' => $token], 200);
			}
			else
			{
				return response()->json(['Credentials Does not Match !'], 406);
			}
		}
	}

    //Resend otp phone
    public function resendOtpPhone(Request $request)
    {
		$validator = Validator::make($request->all(), [
            'country_code' => 'required',
            'phone_number' => 'required|digits:10|numeric',
        ]);
   
        if($validator->fails()){
            $messages = $validator->errors()->messages();
			
			foreach ($messages as $key => $value)
			{
				$error[] = $value[0];
			}
			
            return response()->json($error, 406); 
        }
		
        if(app()->environment('production'))
		{
			$otp = rand(1111, 9999);
		}
		else
		{
			$otp = '1234';
		}

		$data['phone_number'] = $request->phone_number;
		$data['otp'] = $otp;

        return response()->json(['message' => 'OTP Send Successfully','data' => $data], 200);
    }

    //login with google
    public function loginWithGoogle(Request $request)
    {	
    	$validator = Validator::make($request->all(), [
            'email' => 'required',
            'google_id' => 'required',
        ]);
   
        if($validator->fails()){
            $messages = $validator->errors()->messages();
			
			foreach ($messages as $key => $value)
			{
				$error[] = $value[0];
			}
			
            return response()->json($error, 406); 
        }

        $get_google_user = User::where('google_id', $request->google_id)->first();

		$get_email_user = User::where('email', $request->email)->first();
		
		if($get_google_user)
		{
			if (Auth::loginUsingId($get_google_user->id)) 
			{
				$token = auth()->user()->createToken('Token')->accessToken;
			
				$user = User::find($get_google_user->id);
				$user->device_token = $request->device_token;
				$user->device_os = $request->device_os;
				$user->device_os_version = $request->device_os_version;
				$user->device_model = $request->device_model;
				$user->last_login_at = date("Y-m-d H:i:s");
				$user->save();
			
				$get_user = User::where('id', Auth::guard('web')->user()->id)->first();
				
				return response()->json(['message' => 'Google login successfully.', 'data' => $get_user,'token' => $token], 200);
			}
		}
		else if($get_email_user)
		{
			if (Auth::loginUsingId($get_email_user->id)) 
			{
				$token = auth()->user()->createToken('Token')->accessToken;
				
				$user = User::find($get_email_user->id);
				$user->google_id = $request->google_id;
				$user->device_token = $request->device_token;
				$user->device_os = $request->device_os;
				$user->device_os_version = $request->device_os_version;
				$user->device_model = $request->device_model;
				$user->last_login_at = date("Y-m-d H:i:s");
				$user->save();

				$get_user = User::where('id', Auth::guard('web')->user()->id)->first();
				
				return response()->json(['message' => 'Google login successfully.', 'data' => $get_user,'token' => $token], 200);
			}
		}
		else
		{
			
			$user = new User();
			$user->email = $request->email;
			$user->google_id = $request->google_id;
			$user->device_token = $request->device_token;
			$user->device_os = $request->device_os;
			$user->device_os_version = $request->device_os_version;
			$user->device_model = $request->device_model;
			$user->ip_address = $request->ip_address;
			$user->last_login_at = date("Y-m-d H:i:s");
			$user->save();

			$get_user = User::where('id', $user->id)->first();

        	$token = $user->createToken('Token')->accessToken;
			
			return response()->json(['message' => 'User create successfully.', 'data' => $get_user,'token' =>$token], 200);
		}
	
    }

    //login with facebook
    public function loginWithFacebook(Request $request)
    {	
    	$validator = Validator::make($request->all(), [
            'facebook_id' => 'required',
        ]);
   
        if($validator->fails()){
            $messages = $validator->errors()->messages();
			
			foreach ($messages as $key => $value)
			{
				$error[] = $value[0];
			}
			
            return response()->json($error, 406); 
        }

        $get_google_user = User::where('facebook_id', $request->facebook_id)->first();

		if($get_google_user)
		{
			if (Auth::loginUsingId($get_google_user->id)) 
			{
				$token = auth()->user()->createToken('Token')->accessToken;
			
				$user = User::find($get_google_user->id);
				$user->device_token = $request->device_token;
				$user->device_os = $request->device_os;
				$user->device_os_version = $request->device_os_version;
				$user->device_model = $request->device_model;
				$user->last_login_at = date("Y-m-d H:i:s");
				$user->save();
			
				$get_user = User::where('id', Auth::guard('web')->user()->id)->first();
				
				return response()->json(['message' => 'Facebook login successfully.', 'data' => $get_user,'token' => $token], 200);
			}
		}
		else
		{
			$user = new User();
			$user->facebook_id = $request->facebook_id;
			$user->device_token = $request->device_token;
			$user->device_os = $request->device_os;
			$user->device_os_version = $request->device_os_version;
			$user->device_model = $request->device_model;
			$user->ip_address = $request->ip_address;
			$user->last_login_at = date("Y-m-d H:i:s");
			$user->save();

			$get_user = User::where('id', $user->id)->first();

			$token = $user->createToken('Token')->accessToken;
			
			return response()->json(['message' => 'User create successfully.', 'data' => $get_user,'token' =>$token], 200);
		}
    }

    //login with apple
    public function loginWithApple(Request $request)
    {	
    	$validator = Validator::make($request->all(), [
            'apple_id' => 'required',
        ]);
   
        if($validator->fails()){
            $messages = $validator->errors()->messages();
			
			foreach ($messages as $key => $value)
			{
				$error[] = $value[0];
			}
			
            return response()->json($error, 406); 
        }

        $get_google_user = User::where('apple_id', $request->apple_id)->first();

		if($get_google_user)
		{
			if (Auth::loginUsingId($get_google_user->id)) 
			{
				$token = auth()->user()->createToken('Token')->accessToken;
			
				$user = User::find($get_google_user->id);
				$user->device_token = $request->device_token;
				$user->device_os = $request->device_os;
				$user->device_os_version = $request->device_os_version;
				$user->device_model = $request->device_model;
				$user->last_login_at = date("Y-m-d H:i:s");
				$user->save();
			
				$get_user = User::where('id', Auth::guard('web')->user()->id)->first();
				
				return response()->json(['message' => 'Apple login successfully.', 'data' => $get_user,'token' => $token], 200);
			}
		}
		else
		{
			$user = new User();
			$user->apple_id = $request->apple_id;
			$user->device_token = $request->device_token;
			$user->device_os = $request->device_os;
			$user->device_os_version = $request->device_os_version;
			$user->device_model = $request->device_model;
			$user->ip_address = $request->ip_address;
			$user->last_login_at = date("Y-m-d H:i:s");
			$user->save();

			$get_user = User::where('id', $user->id)->first();

        	$token = $user->createToken('Token')->accessToken;
			
			return response()->json(['message' => 'User create successfully.', 'data' => $get_user,'token' =>$token], 200);
		}
    }

    public function logout(Request $request)
	{
		$user_id = auth()->user()->id;

		$user = User::find($user_id);
		$user->device_token = NULL;
		$user->device_os = NULL;
		$user->device_os_version = NULL;
		$user->device_model = NULL;
		$user->ip_address = NULL;
		$user->last_login_at = NULL;
		$user->save();

		$token = $request->user()->token();
        $token->revoke();

		return response()->json(['message' => 'Logout Successfully'], 200);
	}

	//Forgot Passworde Email
	public function forgotPasswordeEmail(Request $request)
    {
		$validator = Validator::make($request->all(), [
            'email' => 'required|exists:users,email',
        ]);
		
        if ($validator->fails())
		{
            $messages = $validator->errors()->messages();
			
			foreach ($messages as $key => $value)
			{
				$error[] = $value[0];
			}
			
            return response()->json($error, 406);
        }
		else
		{
			$users = User::where('email', $request->email)->first();
			$user = User::find($users->id);
        	
        	if(app()->environment('production'))
			{
			$user->email_otp = rand(1111, 9999);
			}
			else
			{
			$user->email_otp = '1234';
			}
			
        	$user->save();

        	$data['email'] = $users->email;
        	$data['otp'] = $user->email_otp;
			
			return response()->json(['message' => 'Send otp Successfully', 'data' => $data], 200);
		}
    }

    //Forgot Passworde phone number
	public function forgotPasswordePhone(Request $request)
    {
		$validator = Validator::make($request->all(), [
            'country_code' => 'required|exists:users,country_code',
            'phone_number' => 'required|exists:users,phone_number',
        ]);
		
        if ($validator->fails())
		{
            $messages = $validator->errors()->messages();
			
			foreach ($messages as $key => $value)
			{
				$error[] = $value[0];
			}
			
            return response()->json($error, 406);
        }
		else
		{
			$users = User::where('phone_number', $request->phone_number)->first();
			$user = User::find($users->id);
        	
        	if(app()->environment('production'))
			{
			$user->phone_otp = rand(1111, 9999);
			}
			else
			{
			$user->phone_otp = '1234';
			}
			$user->save();
			
        	$data['country_code'] = $users->country_code;
        	$data['phone_number'] = $users->phone_number;
        	$data['otp'] = $user->phone_otp;
			
			return response()->json(['message' => 'Send otp Successfully', 'data' => $data], 200);
		}
    }

    // Verify Forgot Passworde Email
	public function verifyForgotPasswordEmail(Request $request)
    {
		$validator = Validator::make($request->all(), [
            'email' => 'required|exists:users,email',
            'email_otp' => 'required|exists:users,email_otp',
        ]);
		
        if ($validator->fails())
		{
            $messages = $validator->errors()->messages();
			
			foreach ($messages as $key => $value)
			{
				$error[] = $value[0];
			}
			
            return response()->json($error, 406);
        }
		else
		{
			$users = User::where('email', $request->email)->first();
			$user = User::find($users->id);
        	$user->email_otp = NULL;
        	$user->save();

			return response()->json(['message' => 'Verify Email Successfully'], 200);
		}
    }

    // Verify Forgot Passworde phone number
	public function verifyForgotPasswordPhoneNumber(Request $request)
    {
		$validator = Validator::make($request->all(), [
            'country_code' => 'required|exists:users,country_code',
            'phone_number' => 'required|exists:users,phone_number',
            'phone_otp' => 'required|exists:users,phone_otp',
        ]);
		
        if ($validator->fails())
		{
            $messages = $validator->errors()->messages();
			
			foreach ($messages as $key => $value)
			{
				$error[] = $value[0];
			}
			
            return response()->json($error, 406);
        }
		else
		{
			$users = User::where('phone_number', $request->phone_number)->first();
			$user = User::find($users->id);
        	$user->phone_otp = NULL;
        	$user->save();
			
			return response()->json(['message' => 'Verify phone number Successfully'], 200);
		}
    }

    //Reset Password
    public function resetPassword(Request $request)
    {
    	
    	$validator = Validator::make($request->all(), [
    		'id' => 'required',
            'password' => 'required',
        ]);
		
        if ($validator->fails())
		{
            $messages = $validator->errors()->messages();
			
			foreach ($messages as $key => $value)
			{
				$error[] = $value[0];
			}
			
            return response()->json($error, 406);
        }
		else
		{	
			$user = User::find($request->id);

			if($user){

				$user->password = bcrypt($request->password);
        		$user->save();

				return response()->json(['message' => 'Password Reset Successfully','data'=>$user], 200);

			}else{
				return response()->json(['message' => 'User Not Found'], 200);
			}
			
			
		}
    }
}
