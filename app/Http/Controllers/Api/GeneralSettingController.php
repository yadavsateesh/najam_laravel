<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Models\GeneralSetting;
use App\Models\Page;
use App\Models\Faq;
use App\Models\Language;

class GeneralSettingController extends Controller
{
	public function getGeneralSetting(Request $request)
	{
		$data['general_settings'] = GeneralSetting::where('status', 1)->orderBy('id', 'DESC')->get();
		$data['pages'] = Page::where('status', 1)->orderBy('id', 'DESC')->get();
		$data['faqs'] = Faq::where('status', 1)->orderBy('id', 'DESC')->get();
		$data['languages'] = Language::where('status', 1)->orderBy('id', 'DESC')->get();

		return response()->json(['message' => 'data found','data' => $data], 200);
	}
}