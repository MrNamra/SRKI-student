<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\ExamInfo;
use App\Repositories\ExamRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ExamsController extends Controller
{
    private $examRepo;
    public function __construct(ExamRepository $examRepository)
    {
        if(session('lab')){
            return redirect()->route('student.dashboard');
        }
        $this->examRepo = $examRepository;
    }    
    public function index() {
        if(session('exam')){
            if(session('exam')->EndDate > now()){
                session()->forget('exam');
                return view('student.exam');
            }
            $examinfo = ExamInfo::where('exam_id', session('exam')->id);
            $exam = Exam::find(session('exam')->id);
            return view('student.examDashboard', ['exam' => $exam, 'examinfo' => $examinfo->first()]);
        }
        
        return view('student.exam');
    }
    public function login(Request $request) {
        try{
            $data = $this->examRepo->login($request->enrollment_no);
            if($data['success']){
                return response()->json(["success" => true, "message" => "Login Successful!"], 200);
            }
            return response()->json($data);
        }catch(Exception $ex){
            Log::info("ID:-" . time() . "\nError submitting assignment: " . $ex->getMessage() . "\nExam Login");
            return response()->json(["status" => false, "error" => "id: " . time() . "\nError from server please try again or contact faculty!"], 500);
        }
        return view('student.exam');
    }
    public function store(Request $request) {
        $request->validate([
            'file' => 'required|mimes:zip,txt,doc,docx,pdf'
        ], [
            'file.required' => 'Please upload a file.',
            'file.mimes' => 'Only files of type: zip, txt, doc, docx, pdf are allowed.',
        ]);
    
        try {
            $exam_id = $id ?? session('exam')->id;
            $exam = Exam::find($exam_id);
            
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $path = $this->examRepo->storeResponse($file, $request->server->get('REMOTE_ADDR'), $request->id );
    
                return response()->json(['success' => true], 200);
            }
        } catch (Exception $ex) {
            Log::info("ID:-" . time() . "\nError submitting response: " . $ex->getMessage() . "\nStudnet: ". auth()->guard('student')->user()->enrollment_no."\n Exam ID:- " . session("exam")->id);
            return response()->json(["status" => false, "error" => "id: " . time() . "\nError from server please try again or contact faculty!"], 500);
        }
    }
}
