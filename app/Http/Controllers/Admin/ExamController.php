<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Cource;
use App\Models\Exam;
use App\Models\ExamInfo;
use App\Models\Student;
use App\Repositories\ExamRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ExamController extends Controller
{
    private $examRepo;
    public function __construct(ExamRepository $examRepository) {
        $this->examRepo = $examRepository;
    }
    public function exam(){
        $streams = Cource::get();
        return view('admin.exam', ['streams' => $streams]);
    }
    public function examStore(Request $request){
            $request->validate([
                'title' => 'required|string',
                'sub_id' => 'required|numeric',
                'examtype' => 'required',
                'div' => 'required',
                'date' => 'required',
                'file' => 'exclude_if:file,null|file',
                'course_id' => 'required|numeric',
                'sem' => 'required|numeric',
            ]);
            $filePath = null;
            try{
                [$StartTime , $EndTime] = explode(' - ', $request->date);
                $StartTime = Carbon::createFromFormat('m/d/Y h:i A', $StartTime, 'Asia/Kolkata');
                $EndTime = Carbon::createFromFormat('m/d/Y h:i A', $EndTime, 'Asia/Kolkata');

                if ($request->hasFile('file')) {
                    $file = $request->file('file');
                    $originalName = $file->getClientOriginalName();
                    $filePath = 'uploads/exam/' . $originalName;
                
                    // Check if file already exists
                    if (Storage::disk('public')->exists($filePath)) {
                        return response()->json(['status' => false, 'message' => 'File Name already exists']);
                    }
                
                    // Store the file
                    $filePath = $file->storeAs('uploads/exam', $originalName, 'public');
                }
                
                $data = $request->except(['_token', 'date', 'file']);
                $data['id'] = !empty($request->id)?$request->id:null;
                $data['StartTime'] = $StartTime;
                $data['EndTime'] = $EndTime;
                $data['file_path'] = $filePath;

            $data = $this->examRepo->CreteOrUpdate($data);
            return response()->json(['status' => true, 'message' => 'Done']);
        }catch (Exception $ex) {
            if ($filePath && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
            return response()->json(['status' => false, 'message' => $ex->getMessage()], 500);
        }
    }
    public function getExamsList(Request $request){
        try {
            if(!empty($request->id)){
                $data = Exam::with(['subject', 'course', 'examinfo'])->find($request->id);
                return response()->json($data);
            }
            $query = Exam::with(['subject', 'course', 'examinfo'])->orderBy('id', 'desc');
            $totalRecords = $query->count();
            $data = $query->offset($request->start)->limit($request->length)->get();

            return response()->json([
                'draw' => (int)$request->draw,
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $totalRecords,
                'data' => $data
            ]);
        } catch (Exception $ex) {
            return response()->json(['status' => false, 'message' => $ex->getMessage()], 500);
        }
    }
    public function distory(Request $request) {
        try{
            $this->examRepo->distory($request->id);
            return response()->json(['status' => true, 'message' => 'Exam deleted successfully']);
        }catch(Exception $ex){
            return response()->json(['status' => false, 'message' => $ex->getMessage()], 500);
        }
    }
    public function getExamsCandidets(Request $request) {
        try{
            $id = $request->id;
            $data = $this->examRepo->getExamsCandidets($request, $id);
            return response()->json($data->original);
        }catch(Exception $ex){
            return response()->json(['status' => false, 'message' => $ex->getMessage()], 500);
        }
    }
    public function updateCandidetsMarks(Request $request) {
        try{
            $data = $this->examRepo->updateCandidetsMarks($request);
            return response()->json($data->original);
        }catch(Exception $ex){
            return response()->json(['status' => false, 'message' => $ex->getMessage()], 500);
        }
    }
}
