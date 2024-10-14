<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExamController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [App\Http\Controllers\ExamController::class, 'home'])->name('home');
Route::get('/popular-exams', [ExamController::class, 'popularExams'])->name('popular.exams');
Route::get('/all-exams', [ExamController::class, 'allExams'])->name('all.exams');
Route::get('/exams/category/{id}', [ExamController::class, 'showByCategory'])->name('exams.category');
// exam router
Route::get('/exams', [ExamController::class, 'index'])->name('exams.index');
Route::get('/exams/{id}', [ExamController::class, 'show'])->name('exams.show');
Route::post('/exams/{id}/submit', [ExamController::class, 'submitResult'])->name('exams.submit');
//submit
Route::post('/exams/{exam}/submit', [ExamController::class, 'submit'])->name('exam.submit');
Route::get('/exams/{exam}/result', [ExamController::class, 'showResult'])->name('exam.result');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
