<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cource;
use App\Models\TimeTable;
use App\Repositories\TimeTableRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class TimetableController extends Controller
{
    protected $TimeTableRepo;
    public function __construct(TimeTableRepository $timetableRepository){
        $this->TimeTableRepo = $timetableRepository;
    }
    public function index()
    {
        try{
            $courses = Cource::get();
            return view('admin.timetable', ['courses' => $courses]);
        }catch(Exception $ex){
            dd($ex);
        }
    }
    public function store(Request $request)
    {
        $request->validate([
            'day' => 'required|string',
            'div' => 'required',
            'course_id' => 'required|numeric',
            'sem' => 'required|numeric',
            'subject_id' => 'required|numeric',
            'date' => 'required',
        ]);
        try{
            [$StartTime , $EndTime] = explode(' - ', $request->date);
            $StartTime = Carbon::createFromFormat('m/d/Y h:i A', $StartTime, 'Asia/Kolkata');
            $EndTime = Carbon::createFromFormat('m/d/Y h:i A', $EndTime, 'Asia/Kolkata');
            $data = $request->except(['_token', 'date']);

            $data['id'] = !empty($request->id)?$request->id:null;
            $data['StartDate'] = $StartTime->format('Y-m-d H:i:s');
            $data['EndDate'] = $EndTime->format('Y-m-d H:i:s');

            $this->TimeTableRepo->UpdateOrCreate($data);

            return response()->json(['status' => true, 'message' => 'Added successfully']);
        } catch(Exception $ex){
            return response()->json(['status' => false, 'message' => $ex->getMessage()], 500);
        }
    }
    public function TimeTableList(Request $request)
    {
        try{
            $timetable = $this->TimeTableRepo->TimeTableList($request);
            return response()->json($timetable->original);
        } catch (Exception $ex) {
            return response()->json(['status' => false, 'message' => $ex->getMessage()], 500);
        }
    }
    public function edit(Request $request)
    {
    }
    public function destroy(Request $request)
    {
        try{
            $ids = $request->input('id');
            if ($ids) {
                TimeTable::whereIn('id', $ids)->delete(); // Adjust according to your model
                return response()->json(['status' => true]);
            }
            return response()->json(['status' => false, 'message' => 'Pease Select first']);
        } catch (Exception $ex){
            return response()->json(['status' => false, 'message' => $ex->getMessage()], 500);
        }
    }
    public function FindTimeTable(Request $request)
    {
        try{
            $timetable = $this->TimeTableRepo->getTimeTable($request);
            if($timetable){
                return response()->json($timetable);
            }
            return response()->json(["status" => false, "message" => "No data found"], 404);
        } catch (Exception $ex) {
            return response()->json(['status' => false, 'message' => $ex->getMessage()], 500);
        }
    }
}
