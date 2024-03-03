<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Models\Page;

class PageController extends Controller
{
	public function getPage(Request $request)
	{
		$get_page = Page::where('status', 1)->orderBy('id', 'DESC')->get();
		
		return response()->json(['message' => 'Get Page Successfully','data' => $get_page], 200);
	}
}
