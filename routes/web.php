<?php

use App\Http\Controllers\Admin\AssginamnetController;
use App\Http\Controllers\admin\ExamController;
use App\Http\Controllers\Admin\LabController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\TimetableController;
use App\Http\Controllers\CourceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExamsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentsController;
use App\Http\Middleware\InactivityLogout;
use App\Http\Middleware\StudentAuth;
use Illuminate\Support\Facades\Route;

Route::get('/', [StudentsController::class, 'loginpage'])->name('student.login');
Route::post('/', [StudentsController::class, 'login'])->name('stuLogin');
Route::get('exam', [ExamsController::class, 'index'])->name('student.exam');
Route::post('exam', [ExamsController::class, 'login'])->name('student.exam.login');
Route::post('examsubmit', [ExamsController::class, 'store'])->name('student.exam.submit');

Route::middleware([StudentAuth::class, InactivityLogout::class])->prefix('student')->group(function () {
    Route::post('/studentLogout', [StudentsController::class, 'logout'])->name('student.logout');
    Route::get('/dashboard', [StudentsController::class, 'index'])->name('student.dashboard');
    Route::post('/dashboard', [StudentsController::class, 'submitAssignment'])->name('upload.assignment');
    Route::get('/uploaded', [StudentsController::class, 'uploadedAssignment'])->name('uploaded.assignment');
    Route::get('/wow', function () { session(['lastActivity' => time()]); return response()->json(['success' => true]); })->name('wow');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('assignments', [AssginamnetController::class, 'index'])->name('assignments');
    Route::get('assignmentslist', [LabController::class, 'list'])->name('getAssignmentList');
    Route::get('getProjectSubmissions', [LabController::class, 'projectSubmissions'])->name('getProjectSubmissions');
    Route::post('assignments', [LabController::class, 'store'])->name('upload-assignment');
    Route::post('updateassignments', [LabController::class, 'update'])->name('update-assignment');
    Route::delete('deleteassignment', [LabController::class, 'delete'])->name('deleteAssignment');
    Route::get('editassignment/{id}', [LabController::class, 'getAssignmentByID'])->name('editAssignment');
    Route::get('students', [StudentController::class, 'index'])->name('students');
    Route::post('students', [StudentController::class, 'store'])->name('add-students');
    Route::get('getSudentsList', [StudentController::class, 'getSudentsList'])->name('get-students-list');
    Route::get('getSutudent', [StudentController::class, 'getSudentById'])->name('getSutudent');
    Route::delete('deleteSutudent', [StudentController::class, 'deleteSudentById'])->name('delete-student');
    Route::post('updateSutudent', [StudentController::class, 'updateSudentById'])->name('update-student');
    Route::post('promotestudents', [StudentController::class, 'promoteSudents'])->name('promote-students');
    Route::post('demotestudents', [StudentController::class, 'demoteSudents'])->name('demote-students');
    Route::get('courses', [CourceController::class, 'index'])->name('course');
    Route::get('getsubjects', [CourceController::class, 'getSubjects'])->name('getSubjects');
    Route::get('getsubject', [CourceController::class, 'getSubject'])->name('getSubject');
    Route::delete('subject', [CourceController::class, 'deleteSubject'])->name('delete-subject');
    Route::post('courses', [CourceController::class, 'addcourse'])->name('add-course');
    Route::post('addSub', [CourceController::class, 'addSubject'])->name('add-subject');
    
    Route::get('exams', [ExamController::class, 'exam'])->name('exam');
    Route::post('exams', [ExamController::class, 'examStore'])->name('examCreateOrUpdate');
    Route::get('examlist', [ExamController::class, 'getExamsList'])->name('getExamStudents');
    Route::delete('exams', [ExamController::class, 'distory'])->name('deleteExam');
    
    Route::get('timetable', [TimetableController::class, 'index'])->name('timetable');
    Route::post('timetable', [TimetableController::class, 'store'])->name('add-timetable');
    Route::get('gettimetable', [TimetableController::class, 'TimeTableList'])->name('getTimetable');
    Route::delete('deleteTimeTable', [TimetableController::class, 'destroy'])->name('deleteTimeTable');
    Route::get('findtimetable', [TimetableController::class, 'FindTimeTable'])->name('searchlab');
    
    Route::get('report', [ReportController::class, 'index'])->name('report');
    Route::get('getreport', [ReportController::class, 'GenerateReport'])->name('genreport');
});

require __DIR__.'/auth.php';
