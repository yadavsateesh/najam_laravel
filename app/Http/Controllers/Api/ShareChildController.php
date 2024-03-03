<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\ShareChild;
use Illuminate\Support\Facades\Crypt;

class ShareChildController extends Controller
{
	public function shareChild(Request $request)
    {
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
        
        $share_child = new ShareChild();
        $share_child->child_id = $request->child_id;
        $share_child->qr_code = bcrypt($request->qr_code);
        $share_child->is_skill = $request->is_skill; 
        $share_child->is_issue = $request->is_issue; 
        $share_child->is_score = $request->is_score; 
        $share_child->is_gifts = $request->is_gifts; 
        $share_child->save();
    	
        return response()->json(['message' => 'Share Child Successfully','data' => $share_child], 200);
    }

    
}