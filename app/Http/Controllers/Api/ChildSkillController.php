<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\ChildSkill;

class ChildSkillController extends Controller
{
	public function addChildSkill(Request $request)
    {
    	$validator = Validator::make($request->all(), [
            'skill_id' => 'required',
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
        
        $childskill = new ChildSkill();
        $childskill->child_id = $request->child_id;
        $childskill->skill_id = $request->skill_id;
        $childskill->points = $request->points; 
        $childskill->save();
    	
        return response()->json(['message' => 'Add Child Successfully','data' => $childskill], 200);
    }

    public function getAllChildSkill(Request $request)
    {
    	$childskill = ChildSkill::orderBy('id', 'DESC')->get();

    	return response()->json(['message' => 'Get All Child Successfully','data' => $childskill], 200);
    }
}