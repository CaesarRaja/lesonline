<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\MentorController;
use App\Http\Controllers\MentorSettingsController;
use App\Http\Controllers\MidtransController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicController::class, 'landing'])->name('landing');
Route::get('/mentors', [PublicController::class, 'mentors'])->name('mentors.index');
Route::get('/mentors/{mentor}', [PublicController::class, 'mentorDetail'])->name('mentors.detail');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware('role:student')->prefix('student')->name('student.')->group(function () {
        Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('dashboard');
        Route::post('/book/{schedule}', [StudentController::class, 'bookSchedule'])->name('book');
        Route::get('/payment/success', [StudentController::class, 'paymentSuccess'])->name('payment.success');
        Route::get('/payments', [StudentController::class, 'payments'])->name('payments');
        Route::get('/materials', [StudentController::class, 'materials'])->name('materials');
        Route::get('/materials/{material}/download', [StudentController::class, 'downloadMaterial'])->name('materials.download');
        Route::post('/favorite/{mentor}', [StudentController::class, 'toggleFavorite'])->name('favorite.toggle');
        Route::post('/coupon/apply', [StudentController::class, 'applyCoupon'])->name('coupon.apply');
        Route::post('/cancel/{transaction}', [StudentController::class, 'requestCancel'])->name('cancel');
        Route::post('/review/{transaction}', [StudentController::class, 'storeReview'])->name('review.store');
        Route::put('/reschedule/{transaction}', [StudentController::class, 'rescheduleSchedule'])->name('reschedule');
        Route::post('/pay/{transaction}', [StudentController::class, 'payPending'])->name('pay');
    });

    Route::middleware(['auth', 'verified'])->prefix('chat')->name('chat.')->group(function () {
        Route::get('/conversations', [ChatController::class, 'conversations'])->name('conversations');
        Route::get('/{user}', [ChatController::class, 'index'])->name('fetch');
        Route::post('/send', [ChatController::class, 'send'])->name('send');
    });

    Route::middleware('role:mentor')->prefix('mentor')->name('mentor.')->group(function () {
        Route::get('/dashboard', [MentorController::class, 'dashboard'])->name('dashboard');
        Route::get('/schedules', [MentorController::class, 'schedules'])->name('schedules');
        Route::get('/withdrawals', [MentorController::class, 'withdrawals'])->name('withdrawals');
        Route::get('/export-pdf', [MentorController::class, 'exportPdf'])->name('export-pdf');
        Route::get('/materials', [MentorController::class, 'materials'])->name('materials');
        Route::get('/materials/{material}/download', [MentorController::class, 'downloadMaterial'])->name('materials.download');
        Route::put('/materials/{material}', [MentorController::class, 'updateMaterial'])->name('materials.update');
        Route::delete('/materials/{material}', [MentorController::class, 'deleteMaterial'])->name('materials.destroy');
        Route::post('/withdrawal', [MentorController::class, 'requestWithdrawal'])->name('withdrawal');
        Route::get('/settings', [MentorSettingsController::class, 'edit'])->name('settings');
        Route::put('/settings', [MentorSettingsController::class, 'update'])->name('settings.update');

        Route::middleware('mentor.verified')->group(function () {
            Route::post('/schedule', [MentorController::class, 'updateSchedule'])->name('schedule.store');
            Route::post('/schedule/{schedule}/toggle', [MentorController::class, 'toggleException'])->name('schedule.toggle');
            Route::post('/materials/upload', [MentorController::class, 'uploadMaterial'])->name('materials.upload');
        });
    });

    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
        Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
        Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');
        Route::get('/verifications', [AdminController::class, 'verifications'])->name('verifications');
        Route::post('/verifications/{user}', [AdminController::class, 'verifyMentor'])->name('verify');
        Route::get('/fees', [AdminController::class, 'fees'])->name('fees');
        Route::post('/fees', [AdminController::class, 'updateFees'])->name('fees.update');
        Route::get('/withdrawals', [AdminController::class, 'withdrawals'])->name('withdrawals');
        Route::post('/withdrawals/{withdrawal}', [AdminController::class, 'resolveWithdrawal'])->name('withdrawals.resolve');
        Route::get('/reviews', [AdminController::class, 'reviews'])->name('reviews');
        Route::delete('/reviews/{review}', [AdminController::class, 'deleteReview'])->name('reviews.destroy');
        Route::get('/export-transactions-pdf', [AdminController::class, 'exportTransactionsPdf'])->name('export-transactions-pdf');
    });
});

Route::post('/midtrans/callback', [MidtransController::class, 'callback'])->name('midtrans.callback');

require __DIR__.'/auth.php';
