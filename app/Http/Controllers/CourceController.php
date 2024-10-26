<?php

namespace App\Http\Controllers;

use App\Models\Cource;
use App\Models\Subject;
use App\Repositories\AssginamnetRepository;
use App\Repositories\CourceRepository;
use App\Repositories\Interface\SubjectRepository;
use App\Repositories\StudentRepository;
use App\Repositories\SubjectRepository as RepositoriesSubjectRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CourceController extends Controller
{
    private $courceRepo,
            $subjectRepo;
    public function __construct(CourceRepository $courceRepository, RepositoriesSubjectRepository $subjectRepository) {
        $this->courceRepo = $courceRepository;
        $this->subjectRepo = $subjectRepository;
    }
    public function index()
    {
        $streams = Cource::get();
        return view('admin.courses', ['streams' => $streams]);
    }
    public function getSubject(Request $request)
    {
        $id = $request->id;
        if($id){
            $res = Subject::find($id);
        }else{
            $res = Subject::where('course_id', $request->cource_id)->where('sem', $request->sem)->get();
        }
        // Prepare the response
        return response()->json($res);
    }
    public function getSubjects(Request $request)
    {
        $searchValue = $request->input('search.value');

        $query = Subject::with('course');

        if ($searchValue) {
            $query->where('name', 'LIKE', "%{$searchValue}%")
                ->orWhere('sem', 'LIKE', "%{$searchValue}%")
                ->orWhere('subject_code', 'LIKE', "%{$searchValue}%")
                ->orWhereHas('course', function($q) use ($searchValue) {
                    $q->where('name', 'LIKE', "%{$searchValue}%");
                });
        }

        $totalRecords = $query->count();

        $perPage = $request->input('length', 10); // Default to 10 if not specified
        $currentPage = $request->input('start', 0) / $perPage + 1; // Calculate current page
        $subjects = $query->paginate($perPage, ['*'], 'page', $currentPage);

        return response()->json([
            'draw' => intval($request->input('draw')), // Send back the draw parameter
            'recordsTotal' => $totalRecords, // Total records before filtering
            'recordsFiltered' => $totalRecords, // Total records after filtering (can be the same if no filtering)
            'data' => collect($subjects->items())->map(function($subject) {
                return [
                    'id' => $subject->id,
                    'subject_code' => $subject->name ." (".$subject->subject_code.")", // Fetch stream name
                    'cource_name' => $subject->course->name,
                    'name' => $subject->name,
                    'sem' => $subject->sem,
                ];
            }),
        ]);
    }
    public function addcourse(Request $request){
        try{
            $request->validate([
                'name' => 'required|string',
                'no_of_sem' => 'required|numeric'
            ]);
            $status =$this->courceRepo->createOrUpdate($request->except('_token'));
            if($status){
                return response()->json([
                'status' => true,
                'message' => 'Stream saved successfully'
                ], 200);
            }
            return response()->json([
                'status' => false,
                'message' => 'Fail'
            ], 400);
        }catch(Exception $e){
            return response()->json([
            'message' => 'Error saving data',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function addSubject(Request $request){
        try{
            $request->validate([
                'course_id' => 'required',
                'sem' => 'required',
                'name' => 'required',
                'subject_code' => 'required',
            ]);
            $this->subjectRepo->createOrUpdate($request->except(['_token', 'id']), $request->input('id'));
            return response()->json(['status' => 'success', 'message' => 'Subjects added successfully!']);
        }catch(Exception $e){
            return response()->json(['status' => 'fail', 'message' => 'Failed to add subjects: ' . $e->getMessage()], 500);
        }
    }
    public function deleteSubject(Request $request){
        try{
            $this->subjectRepo->remove($request->id);
            return response()->json(['status' => 'success', 'message' => 'Subjects added successfully!']);
        }catch(Exception $e){
            return response()->json(['message' => 'Failed to add subjects: ' . $e->getMessage()], 500);
        }
    }
}
