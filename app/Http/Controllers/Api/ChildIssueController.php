<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\ChildIssue;

class ChildIssueController extends Controller
{
	public function addChildIssue(Request $request)
    {
    	$validator = Validator::make($request->all(), [
            'issue_id' => 'required',
            'points' => 'required|numeric',
        ]);
   
        if($validator->fails()){
            $messages = $validator->errors()->messages();
            
            foreach ($messages as $key => $value)
            {
                $error[] = $value[0];
            }
            
            return response()->json($error, 406); 
        }
        
        $childskill = new ChildIssue();
        $childskill->child_id = $request->child_id;
        $childskill->issue_id = $request->issue_id;
        $childskill->points = $request->points; 
        $childskill->save();
    	
        return response()->json(['message' => 'Add Issue Successfully','data' => $childskill], 200);
    }

    public function getAllChildIssue(Request $request)
    {
    	$childskill = ChildIssue::orderBy('id', 'DESC')->get();

    	return response()->json(['message' => 'Get All Issue Successfully','data' => $childskill], 200);
    }
}