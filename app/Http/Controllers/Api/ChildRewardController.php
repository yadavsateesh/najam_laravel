<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\ChildReward;
use File;
use Str;

class ChildRewardController extends Controller
{
    //Add Child Reward
	public function addChildReward(Request $request)
    {
    	$validator = Validator::make($request->all(), [
            'reward_name' => 'required',
            'reward_points' => 'required|numeric',
        ]);
   
        if($validator->fails()){
            $messages = $validator->errors()->messages();
            
            foreach ($messages as $key => $value)
            {
                $error[] = $value[0];
            }
            
            return response()->json($error, 406); 
        }
        
        $child_reward = new ChildReward();
        $child_reward->child_id = $request->child_id;
        $child_reward->reward_name = $request->reward_name;
        $child_reward->reward_points = $request->reward_points;

        if ($request->hasFile('reward_image')) 
        {
            $reward_image = Str::uuid() . '.' . $request->reward_image->getClientOriginalExtension();
            $request->reward_image->storeAs('public/rewardimage', $reward_image);
            $child_reward->reward_image = asset('storage/rewardimage/'. $reward_image);
        }

        $child_reward->save();
    	
        return response()->json(['message' => 'Add Child Reward Successfully','data' => $child_reward], 200);
    }

    //Edit Child Reward
    public function editChildReward(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'reward_name' => 'required',
            'reward_points' => 'required|numeric',
        ]);
   
        if($validator->fails()){
            $messages = $validator->errors()->messages();
            
            foreach ($messages as $key => $value)
            {
                $error[] = $value[0];
            }
            
            return response()->json($error, 406); 
        }
        
        $child_reward = ChildReward::find($request->id);
        $child_reward->child_id = $request->child_id;
        $child_reward->reward_name = $request->reward_name;
        $child_reward->reward_points = $request->reward_points;

        if ($request->hasFile('reward_image')) 
        {   
            $basePath = public_path();
            $filePath = str_replace(url('/'), '', $child_reward->reward_image);
            // Delete the file
            if (File::exists($basePath . $filePath)) {
                File::delete($basePath . $filePath);
            }

            $reward_image = Str::uuid() . '.' . $request->reward_image->getClientOriginalExtension();
            $request->reward_image->storeAs('public/rewardimage', $reward_image);
            $child_reward->reward_image = asset('storage/rewardimage/'. $reward_image);
        }

        $child_reward->save();
        
        return response()->json(['message' => 'Edit Child Reward Successfully','data' => $child_reward], 200);
    }

    //Delete child
    function deleteChildReward(Request $request)
    {
        $child_reward = ChildReward::find($request->id);
        
        if($child_reward)
        {
            $basePath = public_path();
            $filePath = str_replace(url('/'), '', $child_reward->reward_image);
            File::delete($basePath . $filePath);
                
            $child_reward->delete();
            return response()->json(['message' => 'Delete Child Reward Successfully'], 200);
        }
        else
        {
            return response()->json(['message' => 'We cant find a user with that id!'], 406);
        }
    }

    //Get Childr Reward
    function getChildrReward(Request $request)
    {   
        $child_reward = ChildReward::orderBy('id', 'DESC')->limit(5)->get();
        
        return response()->json(['message' => 'Get Child Reward Successfully', 'data' => $child_reward], 406);
    }

    //Get All Childr Reward
    function getAllChildRewards(Request $request)
    {   
        $child_reward = ChildReward::orderBy('id', 'DESC')->get();
        
        return response()->json(['message' => 'Get Child Reward Successfully', 'data' => $child_reward], 406);
    }
}