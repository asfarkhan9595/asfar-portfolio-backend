<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\PortfolioController;
use App\Http\Controllers\Api\V1\ContactController;

Route::prefix('v1')->group(function () {
    Route::get('/profile', [PortfolioController::class, 'profile']);
    Route::get('/projects', [PortfolioController::class, 'projects']);
    Route::get('/projects/{slug}', [PortfolioController::class, 'project']);
    Route::get('/skills', [PortfolioController::class, 'skills']);
    Route::get('/experience', [PortfolioController::class, 'experience']);
    Route::get('/social-links', [PortfolioController::class, 'socialLinks']);
    Route::get('/posts', [PortfolioController::class, 'posts']);
    Route::get('/posts/{slug}', [PortfolioController::class, 'post']);
    Route::get('/settings', [PortfolioController::class, 'settings']);
    Route::post('/contact', [ContactController::class, 'store'])->middleware('throttle:5,1');
    Route::get('/contact-settings', [ContactController::class, 'settings']);
});
