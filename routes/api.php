<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DiscrepancyController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ItemRequestController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\LogHistoryController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\NotificationHistory;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserSettingsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post("/schedule-email", [EmailController::class, 'scheduleEmail'])->middleware('auth:sanctum');
Route::get('/get-logs', [LogHistoryController::class, 'getLogs'])->middleware('auth:sanctum');

Route::prefix("auth")->controller(AuthController::class)->group(function () {
   Route::post("/register", "signup");
   Route::post("/login", "signin");
   Route::post('/forgot-password', 'forgotPassword');
   Route::post('/changepassword', 'changePassword');
});
Route::get("/notification-history", [NotificationHistory::class, 'getHistory'])->middleware('auth:sanctum');

Route::prefix("location")->controller(LocationController::class)->group(function () {
    Route::get("/", "index");
    Route::get("{id}", "show");
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post("/", "store");
        Route::put("{id}", "update");
        Route::delete("{id}", "destroy");
    });
});

Route::prefix("item")->middleware(["auth:sanctum"])->controller(ItemController::class)->group(function () {
    Route::post("scan", "scan");
    Route::get("inventory-report", "inventory_report");
    Route::get("/", "index");
    Route::get("low-stock-items", "low_stock");
    Route::get("{id}", "show");
    Route::post("/", "store");
    Route::patch("{id}", "update");
    Route::post('/upload', 'uploadItemsBulk');
});

Route::prefix("school")->middleware(['auth:sanctum'])->controller(SchoolController::class)->group(function () {
    Route::get("all-schools", "allSchools");
    Route::get("/", "index");
    Route::get("{id}", "show");
    Route::post("/", "store");
    Route::patch("{id}", "update");
    Route::delete("{id}", "destroy");
});

Route::prefix("profile")->middleware(['auth:sanctum'])->controller(ProfileController::class)->group(function () {
    Route::get("/", "get_profile");
    Route::patch("/", "update_profile");
});

Route::prefix("settings")->middleware(["auth:sanctum"])->controller(UserSettingsController::class)->group(function () {
    Route::get("/", "get_settings");
    Route::patch("/", "update_settings");
});

Route::prefix("notification")->middleware(["auth:sanctum"])->controller(NotificationController::class)->group(function () {
    Route::get("/", "get_notifications");
    Route::get("{id}", "get_notification");
    Route::post("/", "create_notification");
    Route::post('/sendnotification', 'sendNotification');
});

Route::prefix("user")->middleware(["auth:sanctum"])->controller(UserController::class)->group(function () {
    Route::get("/", "index");
    Route::get("/get-roles", "create");
    Route::get("{id}", "show");
    Route::post("/", "store");
    Route::patch("{id}", "update");
    Route::post('/upload-users', 'uploadUsers');
    Route::patch("update-status/{id}", "updateStatus");
});

Route::prefix("discrepancy")->middleware(["auth:sanctum"])->controller(DiscrepancyController::class)->group(function () {
    Route::get("/", "index");
    Route::get("{id}", "show");
    Route::post("/", "store");
    Route::delete("delete-multiple", "deleteMultiple");
    Route::delete("{id}", "destroy");
});

Route::prefix("tracking")->middleware(["auth:sanctum"])->controller(TrackingController::class)->group(function () {
    // find schools or items
    Route::post("find-items", "find_items");
    Route::post("find-schools", "find_schools");
    Route::post("export", "exportRecords");
    Route::get("filter", "filter_records");

    Route::get("/", "index");
    Route::get("{id}", "show");
    Route::post("/", "store");
    Route::put('{id}', 'update');
    Route::put('{id}/confirm-delivery', 'confirm_delivery');
});

Route::prefix("item-request")->middleware(["auth:sanctum"])->controller(ItemRequestController::class)->group(function () {
    Route::get("/", "index");
    Route::get("{id}", "show");
    Route::post("/", "store");
});

Route::post("/upload", [\App\Http\Controllers\GeneralController::class, "upload"]);
Route::post('upload-schools', [SchoolController::class, "UploadSchools"]);
