<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Child;
use App\Models\ChildSkill;
use App\Models\ChildIssue;
use File;
use Str;

class ChildController extends Controller
{
    //Add child
    public function addChild(Request $request)
    {
        $user_id = auth()->user()->id;

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'gender' => 'required',
            'date_of_birth' => 'required',
            'image' => '',
        ]);
   
        if($validator->fails()){
            $messages = $validator->errors()->messages();
            
            foreach ($messages as $key => $value)
            {
                $error[] = $value[0];
            }
            
            return response()->json($error, 406); 
        }
        
        $child = new Child();
        $child->user_id = $user_id;
        $child->name = $request->name;
        $child->gender = $request->gender;
        $child->date_of_birth = $request->date_of_birth;
       
        if ($request->hasFile('image')) 
        {
            $image = Str::uuid() . '.' . $request->image->getClientOriginalExtension();
            $request->image->storeAs('public/child', $image);
            $child->image = asset('storage/child/'. $image);
        }
            
        $child->save();
    
        return response()->json(['message' => 'Add Child Successfully','data' => $child], 200);
    }

    //Edit child
    public function editChild(Request $request)
    {
        $user_id = auth()->user()->id;

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'gender' => 'required',
            'date_of_birth' => 'required',
            'image' => '',
        ]);
   
        if($validator->fails()){
            $messages = $validator->errors()->messages();
            
            foreach ($messages as $key => $value)
            {
                $error[] = $value[0];
            }
            
            return response()->json($error, 406); 
        }
        
        $child = Child::find($request->id);
        $child->user_id = $user_id;
        $child->name = $request->name;
        $child->gender = $request->gender;
        $child->date_of_birth = $request->date_of_birth;
        
        if ($request->hasFile('image')) 
        {
            $basePath = public_path();
            $filePath = str_replace(url('/'), '', $child->image);
            // Delete the file
            if (File::exists($basePath . $filePath)) {
                File::delete($basePath . $filePath);
            }

            $image = Str::uuid() . '.' . $request->image->getClientOriginalExtension();
            $request->image->storeAs('public/child', $image);
            $child->image = asset('storage/child/'. $image);
        }
            
        $child->save();
    
        return response()->json(['message' => 'Edit Child Successfully','data' => $child], 200);
    }

    //Delete child
    function deleteChild(Request $request)
    {
        $child = Child::find($request->id);
        
        if($child)
        {
            $basePath = public_path();
            $filePath = str_replace(url('/'), '', $child->image);
            File::delete($basePath . $filePath);
                
            $child->delete();
            return response()->json(['message' => 'Delete Child Successfully'], 200);
        }
        else
        {
            return response()->json(['message' => 'We cant find a user with that id!'], 406);
        }
    }

    //Get All Childs
    function getAllChilds(Request $request)
    {   
        $user_id = auth()->user()->id;
        
        $child = Child::where('user_id', $user_id)->get();

        return response()->json(['message' => 'Get Childs Successfully', 'data' => $child], 406);
    }

    //Get Child Detail
    function getChildDetail(Request $request)
    {   
        $child = Child::where('id', $request->id)->first();

        $child->child_skills = ChildSkill::orderBy('id', 'DESC')->limit(5)->get();
          
        $child->child_issue = ChildIssue::orderBy('id', 'DESC')->limit(5)->get();

        return response()->json(['message' => 'Get Child Detail Successfully', 'data' => $child], 406);
    }
}
