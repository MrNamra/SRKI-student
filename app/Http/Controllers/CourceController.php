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
        $res = Subject::find($id);

        // Prepare the response
        return response()->json($res);
    }
    public function getSubjects(Request $request)
    {
        $searchValue = $request->input('search.value');

        $query = Subject::with('course'); // Eager load the stream relationship

        // Apply search filter if search value exists
        if ($searchValue) {
            $query->where('name', 'LIKE', "%{$searchValue}%");
        }

        // Total records without filtering
        $totalRecords = $query->count();

        // Pagination
        $perPage = $request->input('length', 10); // Default to 10 if not specified
        $currentPage = $request->input('start', 0) / $perPage + 1; // Calculate current page
        $subjects = $query->paginate($perPage, ['*'], 'page', $currentPage);

        // Prepare the response
        return response()->json([
            'draw' => intval($request->input('draw')), // Send back the draw parameter
            'recordsTotal' => $totalRecords, // Total records before filtering
            'recordsFiltered' => $totalRecords, // Total records after filtering (can be the same if no filtering)
            'data' => collect($subjects->items())->map(function($subject) {
                return [
                    // 'id' => $subject->id,
                    'name' => $subject->name,
                    'sem' => $subject->sem,
                    'subject_code' => $subject->subject_code ? $subject->subject_code : 'N/A', // Fetch stream name
                ];
            }), // The actual data with stream name included
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
                'cource_id' => 'required',
                'sem' => 'required',
                'name' => 'required',
                'subject_code' => 'required',
            ]);
            $this->subjectRepo->createOrUpdate($request->except('_token'));
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
