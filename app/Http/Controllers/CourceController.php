<?php

namespace App\Http\Controllers;

use App\Models\Stream;
use App\Models\Subject;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CourceController extends Controller
{
    public function index(){
        $streams = Stream::get();
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

        $query = Subject::with('stream'); // Eager load the stream relationship

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
                    'id' => $subject->id,
                    'name' => $subject->name,
                    'year' => $subject->year,
                    'stream_name' => $subject->stream ? $subject->stream->name : 'N/A', // Fetch stream name
                ];
            }), // The actual data with stream name included
        ]);
    }
    public function addstream(Request $request){
        try{
            $request->validate([
                'stream' => 'required|string'
            ]);
            $status = Stream::create(['stream' => $request->stream]);
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
                'stream' => 'required',
                'year' => 'required',
                'subject' => 'required',
            ]);
            DB::transaction(function () use ($request) {
                foreach($request->stream as $stream){
                    Subject::updateOrCreate(['id' => $request->id], [
                        'name' => $request->subject,
                        'sem' => $request->sem,
                        'stream_id' => $stream,
                    ]);
                }
            });
            return response()->json(['status' => 'success', 'message' => 'Subjects added successfully!']);
        }catch(Exception $e){
            return response()->json(['status' => 'fail', 'message' => 'Failed to add subjects: ' . $e->getMessage()], 500);
        }
    }
    public function deleteSubject(Request $request){
        try{
            Subject::destroy($request->id);
            return response()->json(['status' => 'success', 'message' => 'Subjects added successfully!']);
        }catch(Exception $e){
            return response()->json(['message' => 'Failed to add subjects: ' . $e->getMessage()], 500);
        }
    }
}
