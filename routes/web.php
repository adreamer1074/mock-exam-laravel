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
Route::post('/exams/{id}/submit', [ExamController::class, 'submitResult'])->name('exams.submit');
//submit
Route::post('/exams/{exam}/submit', [ExamController::class, 'submit'])->name('exam.submit');
Route::get('/exams/{exam}/result', [ExamController::class, 'showResult'])->name('exam.result');
// Create, Edit, Update, Delete routes for Exams

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    //profile(CRUD)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // Exam(CRUD)
    Route::get('/exams/create', [ExamController::class, 'create'])->name('exams.create'); // Create form
    Route::post('/exams', [ExamController::class, 'store'])->name('exams.store'); // Store exam
    
    Route::get('/exams/{id}/edit', [ExamController::class, 'edit'])->name('exams.edit'); // Edit form
    Route::put('/exams/{id}', [ExamController::class, 'update'])->name('exams.update'); // Update exa
    Route::delete('/exams/{id}', [ExamController::class, 'destroy'])->name('exams.destroy'); // Delete exam

});
//show を最後に置くが必要。/exams/{id} のような動的なルートが create などの静的なルートよりも上にあると、{id} として「create」をパラメータと誤解する場合があります。
Route::get('/exams/{id}', [ExamController::class, 'show'])->name('exams.show');



require __DIR__.'/auth.php';
