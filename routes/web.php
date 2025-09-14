<?php

use App\Http\Controllers\Admin\TestController;
use App\Http\Controllers\Admin\TestQuestionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DayTaskController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\Admin\FaqController as AdminFaqController;
use App\Http\Controllers\LibraryController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Admin\LibraryManagementController;
/*
|--------------------------------------------------------------------------
| System Utility Routes
|--------------------------------------------------------------------------
*/
Route::get('/clear-cache', function () {
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('cache:clear');
    return "✅ Cache cleared successfully!";
});

Route::get('/storage-link', function () {
    Artisan::call('storage:link');
    return "✅ Storage linked successfully!";
});

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('home');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Library Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    // Users
    Route::get('/library', [LibraryController::class, 'index'])->name('library.index');
    Route::get('/library/preview/{id}', [LibraryController::class, 'preview'])->name('library.preview');
    Route::post('/library/upload', [LibraryController::class, 'upload'])->name('library.upload');
    Route::delete('/library/{filename}', [LibraryController::class, 'destroy'])->name('library.destroy');
    Route::get('/library/download/{id}', [LibraryController::class, 'download'])->name('library.download');

    //ForAdminOnly
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('library/download/{id}', [LibraryManagementController::class, 'download'])
            ->name('library.download');

        Route::get('library', [LibraryManagementController::class, 'index'])->name('library.index');
        Route::get('library/create', [LibraryManagementController::class, 'create'])->name('library.create');
        Route::post('library', [LibraryManagementController::class, 'store'])->name('library.store');
        Route::delete('library/{id}', [LibraryManagementController::class, 'destroy'])->name('library.destroy');

    });

});
/*
|--------------------------------------------------------------------------
| Days & Tasks
|--------------------------------------------------------------------------
*/
Route::get('/day/{id}', [HomeController::class, 'show'])->name('day.show');
Route::post('/day/{id}/upload', [HomeController::class, 'storeUserUpload'])->name('day.upload');
Route::post('/day/{id}/complete', [HomeController::class, 'completeDay'])->name('day.complete');

/*
|--------------------------------------------------------------------------
| Tests
|--------------------------------------------------------------------------
*/
Route::get('/tests', [TestController::class, 'index'])->name('tests.index');
Route::get('/tests/{id}', [TestController::class, 'show'])->name('tests.show');
Route::post('/tests/{test}/submit', [TestController::class, 'submitTest'])->name('tests.submit');

/*
|--------------------------------------------------------------------------
| FAQ
|--------------------------------------------------------------------------
*/
Route::get('/faq', [FaqController::class, 'index'])->name('faq.index');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->middleware(['auth'])->group(function () {
        Route::post('/UploadVideo/{day}',[AdminController::class, 'uploadVideo'])->name('videos.upload');

    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    // Day tasks
    // Route::post('/upload-video/{day}', [AdminController::class, 'uploadVideo'])->name('admin.upload.video');
    Route::post('/toggle-day/{day}', [AdminController::class, 'toggleDayStatus'])->name('admin.toggle.day');
    Route::get('/day-tasks', [DayTaskController::class, 'index'])->name('admin.tasks');
    Route::post('/day-tasks', [DayTaskController::class, 'store'])->name('admin.tasks.store');

    // Tests
    Route::get('test-questions', [TestQuestionController::class, 'index'])->name('admin.test_questions.index');
    Route::get('test-questions/create', [TestQuestionController::class, 'create'])->name('admin.test_questions.create');
    Route::post('test-questions', [TestQuestionController::class, 'store'])->name('admin.test_questions.store');
    Route::delete('/admin/test_questions/{id}', [TestQuestionController::class, 'destroy'])
    ->name('admin.test_questions.destroy');
    
    // Users
    Route::get('/users', [UserController::class, 'index'])->name('admin.users');
    Route::get('/users/file-preview/{upload}', [UserController::class, 'previewFile'])->name('admin.users.file-preview');
    Route::get('/admin/users/{user}/answers', [UserController::class, 'showAnswers'])
     ->name('admin.users.answers-web');
    // FAQ (Admin side)
    Route::resource('faq', AdminFaqController::class)->names([
        'index'   => 'admin.faq.index',
        'create'  => 'admin.faq.create',
        'store'   => 'admin.faq.store',
        'edit'    => 'admin.faq.edit',
        'update'  => 'admin.faq.update',
        'destroy' => 'admin.faq.destroy',
    ]);
});

/*
|--------------------------------------------------------------------------
| Chatbot
|--------------------------------------------------------------------------
*/
Route::get('/chatbot-suggestions', [ChatbotController::class, 'getSuggestions']);
Route::get('/chatbot/questions', [ChatbotController::class, 'getQuestions']); // عرض الأسئلة
Route::post('/chatbot', [ChatbotController::class, 'chat']); // استقبال أسئلة المستخدم

require __DIR__ . '/auth.php';
