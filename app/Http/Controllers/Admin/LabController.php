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
            'file' => 'required|file',
            'cource_id' => 'required|numeric',
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
}
