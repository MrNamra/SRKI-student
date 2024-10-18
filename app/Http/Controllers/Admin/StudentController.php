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
    private $courceRepo,
            $assignamnetRepo,
            $studentRepo,
            $subjectRepo;
    public function __construct(CourceRepository $courceRepository, AssginamnetRepository $assignamnetRepository, StudentRepository $studentRepository, RepositoriesSubjectRepository $subjectRepository) {
        $this->courceRepo = $courceRepository;
        $this->assignamnetRepo = $assignamnetRepository;
        $this->studentRepo = $studentRepository;
        $this->subjectRepo = $subjectRepository;
    }
    public function index(){
        $streams = Cource::get();
        return view('admin.students', ['streams' => $streams]);
    }
    public function store(Request $request){
        $request->validate([
            'file' => 'required|mimes:csv,txt',
            'year' => 'required',
            'stream' => 'required',
        ]);
        try{
            $this->studentRepo->store($request);
            return redirect()->route('students')->with('success', 'File uploaded successfully!');
        }catch(Exception $e){
            return redirect()->route('students')->with('error', 'Error saving data: ' . $e->getMessage());
        }
        // return view('admin.students');
    }
}
