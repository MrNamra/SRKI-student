<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cource;
use App\Models\Stream;
use App\Models\Student;
use App\Repositories\AssginamnetRepository;
use App\Repositories\CourceRepository;
use App\Repositories\Interface\SubjectRepository;
use App\Repositories\StudentRepository;
use App\Repositories\SubjectRepository as RepositoriesSubjectRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    private $studentRepo;
    public function __construct(StudentRepository $studentRepository) {
        $this->studentRepo = $studentRepository;
    }
    public function index(){
        $streams = Cource::get();
        return view('admin.students', ['streams' => $streams]);
    }
    public function store(Request $request){
        set_time_limit(6000);
        $request->validate([
            'file' => 'required|mimes:csv,txt',
            'sem' => 'required',
            'cource_id' => 'required',
        ]);
        try{
            $this->studentRepo->store($request);
            return redirect()->route('students')->with('success', 'Student Added successfully!');
        }catch(Exception $e){
            return redirect()->route('students')->with('error', 'Error saving data: ' . $e->getMessage());
        }
        // return view('admin.students');
    }
    public function getSudentsList(Request $request) {
        try {
            $data = $this->studentRepo->getSudentsList($request);
            return response()->json($data->original);
            return response()->json([
                'draw' => intval($request->input('draw')),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $filteredRecords,
                'data' => collect($students->items())->map(function($student) {
                    return [
                        'enrollment_no' => $student->enrollment_no,
                        'ip' => $student->ip,
                        'name' => $student->name, // Use 'course_name' for consistency
                        'course_name' => $student->course->name, // Use 'course_name' for consistency
                        'sem' => $student->sem,
                        'div' => $student->div,
                    ];
                }),
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error Getting data: ' . $e->getMessage()], 500);
        }
    }
    public function getSudentById(Request $request){
        try{
            $data =  $this->studentRepo->getSudentById($request->id);
            return response()->json($data);
        }catch(Exception $e){
            return response()->json(['message' => 'Error Getting data: ' . $e->getMessage()], 500);
        }
    }
    public function deleteSudentById(Request $request){
        try{
            $this->studentRepo->deleteSudentById($request->id);
            return response()->json(['status' => 'success', 'message' => 'deleted']);
        }catch(Exception $e){
            return response()->json(['status' => 'error', 'message' => 'Error Getting data: ' . $e->getMessage()], 500);
        }
    }
    public function updateSudentById(Request $request) {
        try{
            $this->studentRepo->updateSudentById($request);
            return response()->json(['status'=> 'success', 'message' => 'updated']);
        }catch(Exception $e){
            return response()->json(['status'=> 'fail', 'message' => 'Error Getting data: ' . $e->getMessage()], 500);
        }
    }
    public function promoteSudents() {
        set_time_limit(6000);
        try {
            $data = $this->studentRepo->promoteSudents();
            return response()->json(['status' => 'success', 'message' => 'updated']);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error Getting data: ' . $e->getMessage()], 500);
        }
    }
    public function demoteSudents() {
        set_time_limit(6000);
        try {
            $data = $this->studentRepo->demoteSudents();
            return response()->json(['status' => 'success', 'message' => 'updated']);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error Getting data: ' . $e->getMessage()], 500);
        }
    }
}
