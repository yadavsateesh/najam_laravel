<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Models\Faq;

class FaqController extends Controller
{
	public function getFaq(Request $request)
	{
		$language = $request->header('language');
		
        $get_faq = Faq::where('status', 1)->orderBy('id', 'DESC')->get();
		
		return response()->json(['message' => 'Get Faq Successfully.','data' => $get_faq], 200);
	}
}
