<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Skills;

class SkillsController extends Controller
{
	public function allSkills(Request $request)
    {
    	$skills = Skills::get();
    	
    	return response()->json(['message' => 'Get Skills Successfully','data' => $skills], 200);
    }
}