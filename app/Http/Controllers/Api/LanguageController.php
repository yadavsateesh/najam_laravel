<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Models\Language;

class LanguageController extends Controller
{
	public function getLanguageName(Request $request)
	{
		$get_language = Language::where('status', 1)->orderBy('id', 'DESC')->get();
		
		return response()->json(['message' => 'Get Language Successfully','data' => $get_language], 200);
	}
}
