<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\SkillController;
use App\Http\Controllers\Admin\SkillCategoryController;
use App\Http\Controllers\Admin\ExperienceController;
use App\Http\Controllers\Admin\SocialLinkController;
use App\Http\Controllers\Admin\ResumeController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ProjectCategoryController;
use App\Http\Controllers\Admin\TechnologyController;
use App\Http\Controllers\Admin\AdminContactMessageController;
use App\Http\Controllers\Admin\AdminContactSettingsController;

Route::get('/', function () { return redirect()->route('admin.dashboard'); });

// Admin Authentication Routes
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::get('admin/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('admin/login', [AuthController::class, 'login'])->name('admin.login.submit')->middleware('throttle:5,1');
Route::post('admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/', function() { return redirect()->route('admin.dashboard'); });
    Route::get('/dashboard', function () { return view('admin.dashboard'); })->name('dashboard');
    Route::get('profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('projects/images/{id}', [ProjectController::class, 'destroyImage'])->name('projects.images.destroy');
    Route::post('projects/images/bulk-delete', [ProjectController::class, 'bulkDestroyImages'])->name('projects.images.bulk-destroy');
    Route::resource('projects', ProjectController::class);
    Route::patch('project-categories/{id}/toggle-active', [ProjectCategoryController::class, 'toggleActive'])->name('project-categories.toggle-active');
    Route::resource('project-categories', ProjectCategoryController::class)->except(['show']);
    Route::resource('technologies', TechnologyController::class)->except(['show']);
    Route::patch('skill-categories/{id}/toggle-active', [SkillCategoryController::class, 'toggleActive'])->name('skill-categories.toggle-active');
    Route::resource('skill-categories', SkillCategoryController::class)->except(['show']);
    Route::patch('skills/{id}/toggle-active', [SkillController::class, 'toggleActive'])->name('skills.toggle-active');
    Route::resource('skills', SkillController::class);
    Route::patch('experience/{id}/toggle-published', [ExperienceController::class, 'togglePublished'])->name('experience.toggle-published');
    Route::resource('experience', ExperienceController::class)->except(['show']);
    Route::patch('social-links/{id}/toggle-active', [SocialLinkController::class, 'toggleActive'])->name('social-links.toggle-active');
    Route::resource('social-links', SocialLinkController::class)->except(['show']);
    Route::patch('resume/{id}/toggle-active', [ResumeController::class, 'toggleActive'])->name('resume.toggle-active');
    Route::resource('resume', ResumeController::class);
    Route::patch('contact-messages/{id}/status', [AdminContactMessageController::class, 'updateStatus'])->name('contact-messages.update-status');
    Route::resource('contact-messages', AdminContactMessageController::class)->only(['index', 'show', 'destroy']);
    Route::get('contact-settings', [AdminContactSettingsController::class, 'index'])->name('contact-settings.index');
    Route::post('contact-settings', [AdminContactSettingsController::class, 'update'])->name('contact-settings.update');
    Route::post('settings/update-all', [SettingController::class, 'updateAll'])->name('settings.update-all');
    Route::post('settings/custom', [SettingController::class, 'storeCustom'])->name('settings.store-custom');
    Route::patch('posts/{post}/toggle-publish', [\App\Http\Controllers\Admin\PostController::class, 'togglePublish'])->name('posts.toggle-publish');
    Route::patch('posts/{post}/toggle-trending', [\App\Http\Controllers\Admin\PostController::class, 'toggleTrending'])->name('posts.toggle-trending');
    Route::resource('posts', \App\Http\Controllers\Admin\PostController::class);
    Route::resource('settings', SettingController::class)->only(['index', 'destroy']);
});
