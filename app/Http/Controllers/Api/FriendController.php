<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;
use App\Models\Friend;

class FriendController extends Controller
{
	public function addFriend(Request $request)
    {   
        $user_id = auth()->user()->id;

    	$validator = Validator::make($request->all(), [
            'child_id' => 'required',
            'qr_code' => 'required',
        ]);
   
        if($validator->fails()){
            $messages = $validator->errors()->messages();
            
            foreach ($messages as $key => $value)
            {
                $error[] = $value[0];
            }
            
            return response()->json($error, 406); 
        }
        
        $friend = new Friend();
        $friend->user_id = $user_id;
        $friend->child_id = $request->child_id;
        $friend->qr_code = bcrypt($request->qr_code);
        $friend->is_skill = $request->is_skill; 
        $friend->is_issue = $request->is_issue; 
        $friend->is_score = $request->is_score; 
        $friend->is_gifts = $request->is_gifts; 
        $friend->save();
    	
        return response()->json(['message' => 'Add Friend Successfully','data' => $friend], 200);
    }

    //Get All Friend Childs
    public function getAllFriendChilds(Request $request)
    {   
        $user_id = auth()->user()->id;
 
        $friend = Friend::where('user_id', $user_id)->orderBy('id', 'DESC')->get();

        return response()->json(['message' => 'Get All Friend Childs Successfully','data' => $friend], 200);
    }

    //Get Friend Child Detail
    public function getFriendChildDetail(Request $request)
    {   
        $friend = Friend::where('child_id', $request->child_id)->orderBy('id', 'DESC')->get();

        return response()->json(['message' => 'Get Friend Child Detail','data' => $friend], 200);
    }
    
}