<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\SubThemeController;

use App\Http\Controllers\API\SubsubThemeController;
use App\Http\Controllers\API\ContentThemeController;
use App\Http\Controllers\API\ThemeController;
use App\Http\Controllers\API\SuratController;
use App\Http\Controllers\API\HadithController;
use App\Http\Controllers\API\UserController;

// Authentication routes
Route::post('register', [AuthController::class, 'register']); //udh diuji
Route::post('login', [AuthController::class, 'login']); //udh diuji
// Surat routes
Route::get('/surats', [SuratController::class, 'index']); //udh diuji
Route::get('/surats/{slug}', [SuratController::class, 'show']); //udh diuji

// Hadith routes
Route::get('/hadiths', [HadithController::class, 'index']);
Route::get('/hadiths/{slug}', [HadithController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    // Admin Routes
    Route::middleware('admin')->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('themes', ThemeController::class);
        Route::resource('subthemes', SubThemeController::class);
        Route::resource('subsub_themes', SubsubThemeController::class);
        Route::resource('content_themes', ContentThemeController::class);
    });

    // Member Routes
    // Route::middleware('member')->group(function () {
        
    // });

    Route::post('/save-ayat-surats', [SuratController::class, 'saveAyatSurat']); //udh diuji
    Route::post('/search-ayat-surats', [SuratController::class, 'searchAyatSurat']); //belum
    Route::delete('/delete-saved-ayat-surats/{id}', [SuratController::class, 'destroySavedAyatSurat']); //udh diuji

    Route::post('/save-content-themes', [SaveContentThemeController::class, 'saveContentTheme']); //udah diuji
  
    Route::post('/save-ayat-hadiths', [HadithController::class, 'saveAyatHadith']); //udah diuji
    Route::post('/search-ayat-hadiths', [HadithController::class, 'searchAyatHadith']); //belum
    Route::delete('/delete-saved-ayat-hadiths/{id}', [HadithController::class, 'destroySavedAyatHadith']); //udh diuji

    Route::get('profile-user', [AuthController::class, 'viewProfile']);
    Route::post('edit-profile', [AuthController::class, 'editProfile']);
    Route::post('logout', [AuthController::class, 'logout']);
});

