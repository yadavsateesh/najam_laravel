<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/* Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
}); */

Route::middleware(['saveAudit'])->group( function () {
	//Auth
	Route::post('login-with-email', [App\Http\Controllers\Api\AuthController::class, 'loginEmail']);
    Route::post('register-with-email', [App\Http\Controllers\Api\AuthController::class, 'registerEmail']);
    Route::post('check-email', [App\Http\Controllers\Api\AuthController::class, 'checkEmail']);
	Route::post('register-with-phone', [App\Http\Controllers\Api\AuthController::class, 'registerPhone']);
    Route::post('login-with-phone', [App\Http\Controllers\Api\AuthController::class, 'loginPhone']);
    Route::post('check-phone', [App\Http\Controllers\Api\AuthController::class, 'checkPhone']);
    Route::post('resend-otp-phone', [App\Http\Controllers\Api\AuthController::class, 'resendOtpPhone']);
    Route::post('resend-otp-email', [App\Http\Controllers\Api\AuthController::class, 'resendOtpEmail']);
    Route::post('login-with-google', [App\Http\Controllers\Api\AuthController::class, 'loginWithGoogle']);
    Route::post('login-with-facebook', [App\Http\Controllers\Api\AuthController::class, 'loginWithFacebook']);
    Route::post('login-with-apple', [App\Http\Controllers\Api\AuthController::class, 'loginWithApple']);
    Route::post('forgot-password-email', [App\Http\Controllers\Api\AuthController::class, 'forgotPasswordeEmail']);
    Route::post('forgot-password-phone-number', [App\Http\Controllers\Api\AuthController::class, 'forgotPasswordePhone']);
    Route::post('verify-forgot-password-email', [App\Http\Controllers\Api\AuthController::class, 'verifyForgotPasswordEmail']);
    Route::post('verify-forgot-password-phone-number', [App\Http\Controllers\Api\AuthController::class, 'verifyForgotPasswordPhoneNumber']);
    Route::post('reset-password', [App\Http\Controllers\Api\AuthController::class, 'resetPassword']);
 
    //Skills
    Route::get('get-all-skills', [App\Http\Controllers\Api\SkillsController::class, 'allSkills']);

    //Issues
    Route::get('get-all-issues', [App\Http\Controllers\Api\IssuesController::class, 'allIssues']);

    //General setting
    Route::get('get-language', [App\Http\Controllers\Api\LanguageController::class, 'getLanguageName']);
    Route::get('get-faq', [App\Http\Controllers\Api\FaqController::class, 'getFaq']);
    Route::get('get-general-setting', [App\Http\Controllers\Api\GeneralSettingController::class, 'getGeneralSetting']);
    Route::get('get-page', [App\Http\Controllers\Api\PageController::class, 'getPage']);


	Route::middleware('auth:api')->group( function () {
        //User
        Route::get('last-login', [App\Http\Controllers\Api\UserController::class, 'lastLogin']);
        Route::post('update-name', [App\Http\Controllers\Api\UserController::class, 'updateName']);
        Route::post('update-profile-image', [App\Http\Controllers\Api\UserController::class, 'updateProfileImage']);
        Route::post('logout', [App\Http\Controllers\Api\AuthController::class, 'logout']);
        Route::post('delete-account', [App\Http\Controllers\Api\UserController::class, 'deleteAccount']);
        Route::post('update-language', [App\Http\Controllers\Api\UserController::class, 'updateLanguage']);
        Route::post('change-child-lock', [App\Http\Controllers\Api\UserController::class, 'changeChildLock']);
        Route::post('change-notification', [App\Http\Controllers\Api\UserController::class, 'changeNotification']);

        //child
        Route::post('add-child', [App\Http\Controllers\Api\ChildController::class, 'addChild']);
        Route::post('edit-child', [App\Http\Controllers\Api\ChildController::class, 'editChild']);
        Route::post('delete-child', [App\Http\Controllers\Api\ChildController::class, 'deleteChild']);
        Route::get('get-all-childs', [App\Http\Controllers\Api\ChildController::class, 'getAllChilds']);
        Route::post('get-child-detail', [App\Http\Controllers\Api\ChildController::class, 'getChildDetail']);

        //child skill
        Route::post('add-child-skill', [App\Http\Controllers\Api\ChildSkillController::class, 'addChildSkill']);
        Route::get('get-all-child-skill', [App\Http\Controllers\Api\ChildSkillController::class, 'getAllChildSkill']);

        //child issue
        Route::post('add-child-issue', [App\Http\Controllers\Api\ChildIssueController::class, 'addChildIssue']);
        Route::get('get-all-child-issue', [App\Http\Controllers\Api\ChildIssueController::class, 'getAllChildIssue']);

        //child reward
        Route::post('add-child-reward', [App\Http\Controllers\Api\ChildRewardController::class, 'addChildReward']);
        Route::post('edit-child-reward', [App\Http\Controllers\Api\ChildRewardController::class, 'editChildReward']);
        Route::post('delete-child-reward', [App\Http\Controllers\Api\ChildRewardController::class, 'deleteChildReward']);
        Route::get('get-child-reward', [App\Http\Controllers\Api\ChildRewardController::class, 'getChildrReward']);
        Route::get('get-all-child-rewards', [App\Http\Controllers\Api\ChildRewardController::class, 'getAllChildRewards']);

        //share-child
        Route::post('share-child', [App\Http\Controllers\Api\ShareChildController::class, 'shareChild']);

        //add-friend
        Route::post('add-friend', [App\Http\Controllers\Api\FriendController::class, 'addFriend']);
        Route::get('get-all-friend-childs', [App\Http\Controllers\Api\FriendController::class, 'getAllFriendChilds']);
        Route::post('get-friend-child-detail', [App\Http\Controllers\Api\FriendController::class, 'getFriendChildDetail']);
	});
}); 

