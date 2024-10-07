<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Stream;
use App\Models\Student;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    public function index(){
        $streams = Stream::get();
        return view('admin.students', ['streams' => $streams]);
    }
    public function store(Request $request){
        $request->validate([
            'file' => 'required|mimes:csv,txt',
            'year' => 'required',
            'stream' => 'required',
        ]);
        try{
            if ($request->file('file')) {
                $file = $request->file('file');
                $path = $file->store('uploads/csv', 'public');
                
                $data = array_map('str_getcsv', file(storage_path('app/public/uploads/csv/' . basename($path))));
                $header = $data[0];
                unset($data[0]);
                $records = [];

                foreach ($data as $row) {
                    $records[] = [
                        'en_no' => $row[0],
                        'name' => $row[1],
                        'email' => $row[2],
                        'stream_id' => $row[3],
                        'sem' => $row[4],
                        'ip' => $row[5],
                    ];
                }

                DB::beginTransaction();
                Student::insert($records);
                DB::commit();

                return redirect()->route('students')->with('success', 'File uploaded successfully!');
        } else {
            return redirect()->route('students')->with('error', 'No file uploaded');
            }
        }catch(Exception $e){
            DB::rollBack();
            return response()->json([
               'message' => 'Error saving data',
                'error' => $e->getMessage()
            ], 500);
        }
        // return view('admin.students');
    }
}
