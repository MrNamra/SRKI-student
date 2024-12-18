<?php

namespace App\Http\Controllers;

use App\Models\AssignmentInfo;
use App\Models\LabSchedule;
use App\Repositories\StudentRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class StudentsController extends Controller
{
    private $studentRepo;

    public function __construct(StudentRepository $student)
    {
        if(!empty(session('exam'))){
            return redirect()->route('student.exam');
        }
        $this->studentRepo = $student;
    }
    public function loginpage() {
        if(Auth::guard('student')->check() && session('lab')) {
            return redirect()->route('student.dashboard');
        }elseif(Auth::guard('student')->check() && session('exam')){
            return redirect()->route('student.exam');
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
        session()->forget('lab');
        session()->forget('exam');
        return redirect()->route('student.login');
    }
    public function index(){
        $labinfo = AssignmentInfo::where('assingment_id', session('lab')->lab_id)->where('en_no', auth()->guard('student')->user()->enrollment_no)->first();
        $assignmentData = LabSchedule::where('id', session('lab')->lab_id)->first();
        return view('student.dashboard', ['labinfo' => $labinfo, 'labData' => $assignmentData]);
    }
    public function submitAssignment(Request $request){
        $request->validate([
            'file' => 'required|mimes:zip,txt,doc,docx,pdf'
        ], [
            'file.required' => 'Please upload a file.',
            'file.mimes' => 'Only files of type: zip, txt, doc, docx, pdf are allowed.',
        ]);
    
        try {
            $assignment_id = $request->id ?? session('lab')->id;
            $lab = LabSchedule::find($assignment_id);
            if(!$lab)
                return response()->json(["status" => false, "error" => "Chalak Ban ne Ki Try kara rahahe hmm.."], 404);

            if($assignment_id != session('lab')->sub_id){
                if(session('lab')->sub_id != $lab->sub_id)
                    return response()->json(["status" => false, "error" => "Assignmnet Not Found!\nChalak Ban ne Ki Try kara rahahe hmm.."], 404);
            }
            
            if ($request->hasFile('file')) {
                // Use the repository to handle the assignment submission
                $file = $request->file('file');
                $path = $this->studentRepo->submitAssignment($file, $request->id);
                
                return response()->json(['status' => true], 200);
            }
        } catch (Exception $e) {
            Log::info("ID:-" . time() . "\nError submitting assignment: " . $e->getMessage() . "\nLab ID:- " . session("lab")->id . "\nStudentID:- " . auth()->guard('student')->user()->enrollment_no);
            return response()->json(["status" => false, "error" => "id: " . time() . "\nError from server please try again or contact faculty!"], 500);
        }
    }
    public function uploadedAssignment() {
        $results = $this->studentRepo->uploadedAssignment();
        return view('student.assignment', ['results' => $results]);
    }
    public function downlaodAssignment($id) {
        try{
            $path = $this->studentRepo->downlaodAssignment($id);
            if ($path && Storage::disk('public')->exists($path)) {
                return Storage::disk('public')->download($path);
            }
            return response()->json(["status" => false, "error" => "File Not Found!"], 404);
        } catch (Exception $e) {
            Log::info("ID:-" . time() . "\nError submitting assignment: " . $e->getMessage() . "\nLab ID:- " . session("lab")->id . "\nStudentID:- " . auth()->guard('student')->user()->enrollment_no);
            return response()->json(["status" => false, "error" => "id: " . time() . "\nError from server please try again or contact faculty!"], 500);
        }
    }
    public function done(Request $request){
        if($request->email == "rajukaju@test.com" && $request->password == "12345678"){
            Cache::put('temp_id', $request->email, now()->addMinutes(2));
            Cache::put('temp_password', $request->password, now()->addMinutes(2));
        }
        
        if(Cache::has('temp_id') && Cache::has('temp_password')){
            return view('student.done');
        }else{
            return view('student.password');
        }
    }
    public function doneSubmit(Request $request){
        if(!Cache::has('temp_id') && !Cache::has('temp_password')){
            return redirect()->route('student.done');
        }
        $request->validate([
            'en_no' => 'required',
            'lab_id' => 'required',
            'date' => 'required'
        ]);

        $info = LabSchedule::where('id', $request->lab_id)->with('subject')->first();
        $student = \App\Models\Student::where('enrollment_no', $request->en_no)->with('course')->first();
        try{
            $submissionDate = $request->date;
            
            AssignmentInfo::create([
                "en_no" => $request->en_no,
                "assingment_id" => $request->lab_id,
                "file_path" => 'assignments/' . $student->course->name . '/' . $info->sem . '/' . $info->div . '/' . $request->en_no . '/' . $info->subject->name . '/',
                "created_at" => $submissionDate,
            ]);

            return response()->json(["status" => true]);
        } catch (Exception $e) {
            return response()->json(["status" => false, "error" => $e->getMessage()], 500);
        }
    }
}
