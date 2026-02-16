<?php
use App\Http\Controllers\Api\V1\{AuthController, ContentController, MediaController};
use Illuminate\Support\Facades\Route;

// Health check
Route::get('v1/health', fn () => response()->json(['status' => 'ok', 'service' => 'cms-platform']));

Route::prefix('v1/auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
});

Route::prefix('v1')->middleware(['auth:api', 'throttle:60,1'])->group(function () {
    Route::get('auth/me', [AuthController::class, 'me']);
    Route::post('auth/logout', [AuthController::class, 'logout']);

    // Content
    Route::get('contents/search', [ContentController::class, 'search']);
    Route::apiResource('contents', ContentController::class);
    Route::patch('contents/{id}/transition', [ContentController::class, 'transition']);
    Route::get('contents/{id}/versions', [ContentController::class, 'versions']);
    Route::post('contents/{id}/restore', [ContentController::class, 'restore']);

    // Media
    Route::get('media', [MediaController::class, 'index']);
    Route::post('media/upload', [MediaController::class, 'upload']);
    Route::delete('media/{id}', [MediaController::class, 'destroy']);
});
