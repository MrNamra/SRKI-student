<?php

namespace App\Http\Controllers;

use App\Repositories\StudentRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentsController extends Controller
{
    private $studentRepo;

    public function __construct(StudentRepository $student)
    {
        $this->studentRepo = $student;
    }
    public function loginpage() {
        if(Auth::guard('student')->check()){
            return redirect()->route('student.dashboard');
        }
        return view('welcome');
    }
    public function login(Request $request) {
        $request->validate([
            'enrollment_no' => 'required|string|max:11|min:11'
        ]);
        try{
            $student = $this->studentRepo->login($request->enrollment_no);
            if($student)
                return response()->json(['status' => true, 'success' => 'success', 'message' => 'Access Granted!'], 200);
            return response()->json(['status' => false, 'message' => 'Error While Login!'], 405);
        } catch (\Exception $e){
            return response()->json(['error' => $e->getMessage()], 401);
        }     
    }
    public function logout() {
        Auth::guard('student')->logout();
        session()->flush();
        return redirect()->route('student.login');
    }
    public function index(){
        return view('student.dashboard');
    }
}
