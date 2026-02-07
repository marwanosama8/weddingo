<?php

use App\Http\Controllers\API\PartnerController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PartnerRequestController;
use App\Http\Controllers\PayMobController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('blockuser/{user}', [UserController::class,'blockUser'])->name('user.block');
Route::get('acceptpartner/{partner}', [PartnerController::class,'acceptPartner'])->name('partner.accept');
Route::get('acceptrequset/{partnerrequest}', [PartnerRequestController::class,'acceptRequest'])->name('sub.accept');


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('paying/{integration_id}/{subscription_id}',[PayMobController::class,'checkingOut']);
Route::get('redirect',[PayMobController::class,'processedCallback']);


// new
Route::patch('/fcm-token', [HomeController::class, 'updateToken'])->name('fcmToken');
Route::post('/send-notification',[HomeController::class,'notification'])->name('send.notification');