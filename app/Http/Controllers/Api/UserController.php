<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Models\User;
use File;
use Str;

class UserController extends Controller
{
	//Get Last Login user
    public function lastLogin(Request $request)
	{
		$user = Auth::user();
		if($user)
		{
			return response()->json(['success' => true,'message' => 'Last Login Successfully','data' => $user], 200);
		}
		else
		{
			return response()->json(['message' => "User Not Found"], 406);
		}
	}
	
	//Update name
	public function updateName(Request $request)
	{
		$user_id = auth()->user()->id;
		
		$validator = Validator::make($request->all(), [
            'name' => 'required',
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
			$user = User::find($user_id);
            $user->name = $request->name;
            $user->save();
			
			$get_user = User::find($user_id);
			
			return response()->json(['message' => 'Name Update Successfully', 'data' => $get_user], 200);
		}
	}

	//Update Profile
	public function updateProfileImage(Request $request)
	{
		$user_id = auth()->user()->id;

		$validator = Validator::make($request->all(), [
            'profile_image' => 'required',
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
			$user = User::find($user_id);

            if ($request->hasFile('profile_image')) 
			{
				$basePath = public_path();
	            $filePath = str_replace(url('/'), '', $user->profile_image);
	            // Delete the file
	            if (File::exists($basePath . $filePath)) {
	                File::delete($basePath . $filePath);
	            }

				$profile_image = Str::uuid() . '.' . $request->profile_image->getClientOriginalExtension();
				$request->profile_image->storeAs('public/userImage', $profile_image);
				$user->profile_image = asset('storage/userImage/'. $profile_image);
			}
			
            $user->save();
			
			$get_user = User::find($user_id);
			
			return response()->json(['message' => 'Profile Update Successfully', 'data' => $get_user], 200);
		}
	}

	function deleteAccount(Request $request)
	{
		$user_id = auth()->user()->id;
	
		$user = User::find($user_id);
		
		if($user)
		{
			$basePath = public_path();
            $filePath = str_replace(url('/'), '', $user->profile_image);
         	File::delete($basePath . $filePath);
             	
		    $user->delete();
			return response()->json(['message' => 'Account Delete Successfully'], 200);
		}
		else
		{
			return response()->json(['message' => 'We cant find a user with that id!'], 406);
		}
	}

	public function updateLanguage(Request $request)
    {
		$user_id = auth()->user()->id;
	
		$user = User::find($user_id);
		$user->language_id = $request->language_id;
		$user->save();
		
		return response()->json(['message' => 'Language Changed Successfully'], 200);
	}

	public function changeChildLock(Request $request)
	{
		$user_id = auth()->user()->id;
		
		$validator = Validator::make($request->all(), [
            'child_lock' => 'required',
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
			$child_lock = User::where('child_lock', 0)->where('id', $user_id)->first();
			
			if($child_lock){

				$user = User::find($user_id);
            	$user->child_lock = $request->child_lock;
            	$user->save();
			
				$get_user = User::find($user_id);
			
				return response()->json(['message' => 'Child lock enable Successfully', 'data' => $get_user], 200);
			}else{

				$user = User::find($user_id);
            	$user->child_lock = $request->child_lock;
            	$user->save();
			
				$get_user = User::find($user_id);
			
				return response()->json(['message' => 'Child lock disable Successfully', 'data' => $get_user], 200);
			}
		}
	}

	public function changeNotification(Request $request)
	{
		$user_id = auth()->user()->id;
		
		$validator = Validator::make($request->all(), [
            'notification' => 'required',
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
			$notification = User::where('notification', 1)->where('id', $user_id)->first();
			
			if($notification){

				$user = User::find($user_id);
            	$user->notification = $request->notification;
            	$user->save();
			
				$get_user = User::find($user_id);
			
				return response()->json(['message' => 'Notification disable Successfully', 'data' => $get_user], 200);
			}else{

				$user = User::find($user_id);
            	$user->notification = $request->notification;
            	$user->save();
			
				$get_user = User::find($user_id);
			
				return response()->json(['message' => 'Notification enable Successfully', 'data' => $get_user], 200);
			}
		}
	}
}
