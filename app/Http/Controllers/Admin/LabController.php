<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LabSchedule;
use App\Repositories\LabRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class LabController extends Controller
{
    private $labRepo;
    public function __construct(LabRepository $labRepository)
    {
        $this->labRepo = $labRepository;
    }
    public function store(Request $request){
        $request->validate([
            'title' => 'required|string',
            'sub_id' => 'required|numeric',
            'div' => 'required',
            'date' => 'required',
            'file' => 'exclude_if:file,null|file',
            'course_id' => 'required|numeric',
            'sem' => 'required|numeric',
        ]);
        try{
            [$StartTime , $EndTime] = explode(' - ', $request->date);
            $StartTime = Carbon::createFromFormat('m/d/Y h:i A', $StartTime, 'Asia/Kolkata');
            $EndTime = Carbon::createFromFormat('m/d/Y h:i A', $EndTime, 'Asia/Kolkata');

            $filePath = null;
            if ($request->hasFile('file')) {
                $filePath = $request->file('file')->store('assignments', 'public');
            }
            
            $data = $request->except(['_token', 'date', 'file']);
            $data['id'] = !empty($request->id)?$request->id:null;
            $data['StartTime'] = $StartTime;
            $data['EndTime'] = $EndTime;
            $data['file_path'] = $filePath;

            $this->labRepo->UpdateOrCreate($data);

            return response()->json(['status' => true, 'message' => 'Assignment Scheduled successfully']);
        }catch(Exception $ex){
            return response()->json(['status' => false, 'message' => $ex->getMessage()], 500);
        }
    }
    public function update(Request $request){
        $request->validate([
            'id' => 'required|numeric',
            'title' => 'required|string',
            'sub_id' => 'required|numeric',
            'div' => 'required',
            'date' => 'required',
            'file' => 'nullable|file',
            'course_id' => 'required|numeric',
            'sem' => 'required|numeric',
        ]);
        try{
            [$StartTime , $EndTime] = explode(' - ', $request->date);
            $StartTime = Carbon::createFromFormat('m/d/Y h:i A', $StartTime, 'Asia/Kolkata');
            $EndTime = Carbon::createFromFormat('m/d/Y h:i A', $EndTime, 'Asia/Kolkata');
            
            $filePath = null;
            if ($request->hasFile('file')) {
                $filePath = $request->file('file')->store('assignments', 'public');
            }
            
            $data = $request->except(['_token', 'date', 'file']);
            $data['id'] = !empty($request->id)?$request->id:null;
            $data['StartTime'] = $StartTime->format('Y-m-d H:i:s');
            $data['EndTime'] = $EndTime->format('Y-m-d H:i:s');;
            if($filePath)
                $data['file_path'] = $filePath;

            $this->labRepo->UpdateOrCreate($data);

            return response()->json(['status' => true, 'message' => 'Assignment Scheduled successfully']);
        }catch(Exception $ex){
            return response()->json(['status' => false, 'message' => $ex->getMessage()], 500);
        }
    }
    public function list(Request $request){
        try{
            $data = $this->labRepo->list($request);
            return response()->json($data->original);
        }catch(Exception $ex){
            return response()->json(['status' => false, 'message' => $ex->getMessage()], 500);
        }
    }
    public function delete(Request $request){
        try{
            $this->labRepo->distory($request->id);
            return response()->json(['status' => true, 'message' => 'Assignment deleted successfully']);
        }catch(Exception $ex){
            return response()->json(['status' => false, 'message' => $ex->getMessage()], 500);
        }
    }
    public function getAssignmentByID($id){
        try{
            $data = $this->labRepo->getAssignmentByID($id);
            // dd($data);
            return response()->json($data);
        }catch(Exception $ex){
            return response()->json(['status' => false, 'message' => $ex->getMessage()], 500);
        }
    }
    public function projectSubmissions(Request $request){
        try {
            $data = $this->labRepo->projectSubmissions($request, $request->id);
            return response()->json($data->original);
        } catch (Exception $ex) {
            return response()->json(['status' => false, 'message' => $ex->getMessage()], 500);
        }
    }
}
