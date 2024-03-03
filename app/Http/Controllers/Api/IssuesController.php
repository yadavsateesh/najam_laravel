<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Issues;

class IssuesController extends Controller
{
	public function allIssues(Request $request)
    {
    	$issues = Issues::get();
    	
    	return response()->json(['message' => 'Get Issues Successfully','data' => $issues], 200);
    }
}