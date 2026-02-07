<?php

use App\Http\Controllers\API\AdvertisementController;
use App\Http\Controllers\API\BlogController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\ChatController;
use App\Http\Controllers\API\FavoriteController;
use App\Http\Controllers\API\ImagesController;
use App\Http\Controllers\API\PartnerController;
use App\Http\Controllers\API\PartnerPriceListController;
use App\Http\Controllers\API\ResservasionController;
use App\Http\Controllers\API\ReviewController;
use App\Http\Controllers\API\SubscriptionController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\PartnerRequestController;
use App\Http\Controllers\PayMobController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;


Broadcast::routes(['middleware' => ['auth:sanctum']]);


// Login Routes
Route::group(['middleware' => 'api'], function () {
    /**
     * Healthcheck
     *
     * Check that the service is up. If everything is okay, you'll get a 200 OK response.
     *
     * Otherwise, the request will fail with a 400 error, and a response listing the failed services.
     *
     * @response 400 scenario="Service is unhealthy" {"status": "down", "services": {"database": "up", "redis": "down"}}
     * @responseField status The status of this API (`up` or `down`).
     * @responseField services Map of each downstream service and their status (`up` or `down`).
     */

    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::get('social/login', [AuthController::class, 'socialLogin']);

    // new forget passwrod routes
    // Route::post('send-otp', [SmsServiceController::class, 'sendOTP']);
    // Route::post('verify-otp', [SmsServiceController::class, 'verifyOTP']);
    // Social login
    // Route::post('facebook/login',[SocialLoginController::class,'facebookLogin']); 

}); // End of Login Routes

Route::middleware(['auth:sanctum', 'api'])->group(function () {
    //logout
    Route::post('logout', [AuthController::class, 'logout']);

    // User Route
    Route::prefix('user')->group(function () {
        Route::get('/', [UserController::class, 'user']);
        Route::post('update', [UserController::class, 'edit']);
        Route::post('update-token', [UserController::class, 'updateDeviceToken']);
    });

    // images route
    Route::prefix('images')->group(function () {
        Route::post('show', [ImagesController::class, 'show']);
        Route::post('updateimage', [ImagesController::class, 'updateImage']);
    });

    // categroy routes
    Route::prefix('category')->group(function () {
        Route::get('/', [CategoryController::class, 'index']);
    });

    // partner routes
    Route::prefix('partner')->group(function () {
        Route::post('show', [PartnerController::class, 'show']);
        Route::post('store', [PartnerController::class, 'store']);
        Route::post('update', [PartnerController::class, 'edit']);
        Route::post('show-by-category', [PartnerController::class, 'showPartnersByCategory']);
    });
    // partner price list routes
    Route::prefix('partner-pricelist')->group(function () {
        Route::post('store', [PartnerPriceListController::class, 'store']);
        Route::post('show', [PartnerPriceListController::class, 'show']);
        Route::post('edit', [PartnerPriceListController::class, 'edit']);
        Route::post('delete', [PartnerPriceListController::class, 'delete']);
    });
    // reviews routes
    Route::prefix('reviews')->group(function () {
        Route::post('count', [ReviewController::class, 'getPartnerReviewsCount']);
        Route::post('all-reviews', [ReviewController::class, 'GetPartnerReviewsAndComments']);
        Route::post('store', [ReviewController::class, 'store']);
    });
    // blog routes
    Route::prefix('blog')->group(function () {
        Route::post('category', [BlogController::class, 'blogIndexByCategory']);
        Route::post('store-gallery', [BlogController::class, 'storeImageToGallery']);
        Route::post('show-gallery', [BlogController::class, 'showPartnerImageGallery']);
        Route::post('show-gallery-by-id', [BlogController::class, 'showPartnerImageGalleryById']);
        Route::post('react', [BlogController::class, 'reactToBlog']);
        Route::post('comment', [BlogController::class, 'commentToBlog']);
        Route::post('delete', [BlogController::class, 'deleteBlog']);
    });

    // resservasion routes
    Route::prefix('resservasion')->group(function () {
        Route::get('user-resservasions', [ResservasionController::class, 'userShowResservasion']);
        Route::post('make-resservasion', [ResservasionController::class, 'makeResservasion']);
        Route::post('user-cancel', [ResservasionController::class, 'userCanelResservasion']);
        Route::post('user-done', [ResservasionController::class, 'userResservasionDone']);
        Route::get('partner-resservasions', [ResservasionController::class, 'partnerShowResservasions']);
        Route::post('partner-accept', [ResservasionController::class, 'partnerAcceptResservasion']);
        Route::post('partner-refuse', [ResservasionController::class, 'partnerRefuseResservasion']);
    });

    // favorite routes
    Route::prefix('favorite')->group(function () {
        Route::get('/', [FavoriteController::class, 'index']);
        Route::post('store', [FavoriteController::class, 'store']);
    });

    // chat routes 
    Route::prefix('chat')->group(function () {
        //user 
        Route::post('create-conversation', [ChatController::class, 'makeConversation']);
        Route::get('user-conversations', [ChatController::class, 'userConversations']);
        Route::post('user-send', [ChatController::class, 'userSendMessage']);
        Route::post('conversation-by-id', [ChatController::class, 'getConversationById']);

        // partner
        Route::post('partner-send', [ChatController::class, 'partnerSendMessage']);
        Route::post('parnter-conversation-by-id', [ChatController::class, 'getPartnerConversationById']);
        Route::post('partner-conversations', [ChatController::class, 'partnerConversations']);

        //get conversation count notifcation
        Route::post('get-conversation-unread-count', [ChatController::class, 'isConversationRead']);
        // send media
        Route::post('user-send-media', [ChatController::class, 'userSendAttachment']);
        Route::post('partner-send-media', [ChatController::class, 'partnerSendAttachment']);
    });

    // subscriptions routes 
    Route::prefix('subscription')->group(function () {
        Route::get('index', [SubscriptionController::class, 'getSubscriptions']);
        Route::post('request-subscription', [PartnerRequestController::class, 'makeRequset']);
    });

    // advertisement routes 
    Route::prefix('advertisement')->group(function () {
        Route::get('index', [AdvertisementController::class, 'index']);
    });
});
