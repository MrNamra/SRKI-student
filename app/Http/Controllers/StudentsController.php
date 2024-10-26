<?php

namespace App\Http\Controllers;

use App\Models\AssignmentInfo;
use App\Models\Cource;
use App\Repositories\StudentRepository;
use ErrorException;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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
            if(!empty($student['error']) && $student['error'])
                return response()->json(['status' => false, 'message' => $student['message']], 405);
            return response()->json(['status' => true, 'success' => 'success', 'message' => 'Access Granted!'], 200);
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
        $labinfo = AssignmentInfo::where('assingment_id', session('lab')->id)->where('en_no', auth()->guard('student')->user()->enrollment_no)->first();
        return view('student.dashboard', ['labinfo' => $labinfo]);
    }
    public function submitAssignment(Request $request){
        $request->validate([
            'file' => 'required|mimes:zip,txt,doc,docx,pdf'
        ], [
            'file.required' => 'Please upload a file.',
            'file.mimes' => 'Only files of type: zip, txt, doc, docx, pdf are allowed.',
        ]);
    
        try {
            if ($request->hasFile('file')) {
                // Use the repository to handle the assignment submission
                $file = $request->file('file');
                $path = $this->studentRepo->submitAssignment($file, session('lab'));
    
                return response()->json(['status' => true], 200);
            }
        } catch (Exception $e) {
            Log::info("ID:-" . time() . "\nError submitting assignment: " . $e->getMessage() . "\nLab ID:- " . session("lab")->id . "\nStudentID:- " . auth()->guard('student')->user()->enrollment_no);
            return response()->json(["status" => false, "error" => "id: " . time() . "\nError from server please try again or contact faculty!"], 500);
        }
    }
}
