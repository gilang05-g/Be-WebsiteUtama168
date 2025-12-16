<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AdminController; // jika ada

// ========== AUTH ROUTES ==========
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// ========== PROTECTED ROUTES ==========
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/profile', function (Request $request) {
        if (! $request->user()) {
            return response()->json([
                "status" => false,
                "message" => "Token tidak valid atau token tidak ditemukan"
            ], 401);
        }

        return response()->json([
            "status" => true,
            "user" => $request->user()
        ]);
    });

    Route::post('/logout', function (Request $request) {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logout berhasil']);
    });
});

// ========== ADMIN ONLY ==========
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return response()->json(['message' => 'Halo Admin']);
    });
});

// ========== USER ONLY ==========
Route::middleware(['auth:sanctum', 'role:user'])->group(function () {
    Route::get('/user/dashboard', function () {
        return response()->json(['message' => 'Halo User']);
    });
});
