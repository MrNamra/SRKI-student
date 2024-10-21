<?php

namespace App\Http\Controllers;

use App\Models\Cource;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $TotalCources = Cource::count();
        $TotalStudents = Student::count();
        $TotalSubjects = Subject::count();
        return view('admin.dashboard', compact('TotalCources', 'TotalStudents', 'TotalSubjects'));
    }
}
