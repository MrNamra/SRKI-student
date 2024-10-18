<?php

use App\Http\Controllers\Admin\AssginamnetController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\CourceController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('admin.dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');
    Route::get('assignments', [AssginamnetController::class, 'index'])->name('assignments');
    Route::get('students', [StudentController::class, 'index'])->name('students');
    Route::post('students', [StudentController::class, 'store'])->name('add-students');
    Route::get('courses', [CourceController::class, 'index'])->name('course');
    Route::get('getsubjects', [CourceController::class, 'getSubjects'])->name('getSubjects');
    Route::get('getsubject', [CourceController::class, 'getSubject'])->name('getSubject');
    Route::delete('subject', [CourceController::class, 'deleteSubject'])->name('delete-subject');
    Route::post('courses', [CourceController::class, 'addcourse'])->name('add-course');
    Route::post('addSub', [CourceController::class, 'addSubject'])->name('add-subject');
    Route::post('students', [StudentController::class, 'store'])->name('add-students');
});

require __DIR__.'/auth.php';
