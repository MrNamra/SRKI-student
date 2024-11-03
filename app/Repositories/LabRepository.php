<?php

namespace App\Repositories;

use App\Interface\LabRepositoryInterface;
use App\Models\AssignmentInfo;
use App\Models\LabSchedule;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;

/**
 * Class LabRepository.
 */
class LabRepository extends BaseRepository implements LabRepositoryInterface
{
    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return LabSchedule::class;
    }
    
    public function UpdateOrCreate($data)
    {
        LabSchedule::UpdateOrCreate(['id' => $data['id']], $data);
    }
    public function list($request)
    {
        $searchValue = $request->input('search.value');

        // Query with relationships
        $query = LabSchedule::with('subject');

        // Apply search filter
        if ($searchValue) {
            $query->where(function($q) use ($searchValue) {
                $q->where('title', 'LIKE', "%{$searchValue}%")
                    ->orWhere('div', 'LIKE', "%{$searchValue}%")
                    ->orWhere('StartTime', 'LIKE', "%{$searchValue}%")
                    ->orWhere('EndTime', 'LIKE', "%{$searchValue}%")
                    ->orWhereHas('subject', function($q) use ($searchValue) {
                        $q->where('name', 'LIKE', "%{$searchValue}%")
                            ->orWhere('subject_code', 'LIKE', "%{$searchValue}%");
                    });
            });
        }
        $query->orderBy('StartTime', 'desc');
        // Total records before filtering
        $totalRecords = LabSchedule::count();

        // Total records after filtering
        $filteredRecords = $query->count();

        // Pagination parameters
        $perPage = $request->input('length', 5); // Default to 5
        $currentPage = ($request->input('start', 0) / $perPage) + 1;

        // Get paginated data
        $labRecords = $query->paginate($perPage, ['*'], 'page', $currentPage);

            // Prepare response data directly
        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => collect($labRecords->items())->map(function($lab) {

                $status = 'N/A';
                if ($lab->StartTime > now()) {
                    $status = "<span class='badge badge-danger'>Upcoming</span>";
                } else if ($lab->StartTime < now() && $lab->EndTime > now()) {
                    $status = "<span class='badge badge-info'>Ongoing</span>";
                } else if ($lab->StartTime < now() && $lab->EndTime < now()) {
                    $status = "<span class='badge badge-success'>Completed</span>";
                }

                $totalStudents = Student::where('div', $lab->div)->count();

                // Get semester from the subject
                $semester = $lab->subject->sem;

                // Count submissions for the specific assignment
                $submittedCount = AssignmentInfo::where('assingment_id', $lab->id) // Assuming lab ID is the assignment ID
                    ->whereHas('students', function($q) use ($semester) {
                        $q->where('sem', $semester);
                    })->count();

                // Calculate submission percentage
                $submissionPercentage = $totalStudents > 0 ? ($submittedCount / $totalStudents) * 100 : 0;
                $percentage = '<div class="progress progress-sm"><div class="progress-bar bg-green" role="progressbar" aria-valuenow="'.round($submissionPercentage, 2) . '" aria-valuemin="0" aria-valuemax="100" style="width: '.round($submissionPercentage, 2).'%"></div></div><small>'.round($submissionPercentage, 2).'% Uploaded</small>';

                return [
                    'id' => $lab->id,
                    'title' => $lab->title,
                    'subject' => $lab->subject->name . " (" . $lab->subject->subject_code . ")",
                    'subject_code' => $lab->subject->subject_code,
                    'sem' => $lab->subject->sem,
                    'div' => $lab->div,
                    'time' => $lab->StartTime . ' TO ' . $lab->EndTime,
                    'status' => $status,
                    'submission_percentage' => $percentage,
                ];
            }),
        ]);
    }
    public function distory($id)
    {
        DB::beginTransaction();
        try{
            AssignmentInfo::where('assingment_id', $id)->delete();
            LabSchedule::where('id', $id)->delete();
            DB::commit();
        }catch(\Exception $e){
            DB::rollBack();
            throw new \Exception('Error deleting data: ' . $e->getMessage());
        }
    }
    public function getAssignmentByID($id){
        $data = LabSchedule::with('subject')->find($id);
        // dd($data);
        return [
            'id' => $data->id,
            'title' => $data->title,
            'dec' => $data->dec,
            'course_id' => $data->subject->course_id,
            'sem' => $data->subject->sem,
            'sub_id' => $data->sub_id,
            'div' => $data->div,
            'date' => date('m/d/Y h:i A', strtotime($data->StartTime)).' - '.date('m/d/Y h:i A', strtotime($data->EndTime)),
        ];
    }
    public function projectSubmissions($request, $id)
    {
        $data = LabSchedule::with('assignment', 'subject')->find($id);
        if (!$data) {
            return response()->json(['error' => 'Lab schedule not found'], 404);
        }

        // Fetch submitted assignments
        $submittedAssignments = AssignmentInfo::where('assingment_id', $data->id)->get();

        // Build the query for all students
        $query = Student::where('div', $data->div)
                        ->where('sem', $data->subject->sem);

        // Apply search filter if present
        if ($request->has('search') && !empty($request->search['value'])) {
            $searchValue = $request->search['value'];
            $query->where(function ($query) use ($searchValue) {
                $query->where('name', 'like', "%{$searchValue}%")
                    ->orWhere('enrollment_no', 'like', "%{$searchValue}%");
            });
        }

        // Get total records before applying pagination
        $totalRecords = $query->count();

        // Apply pagination
        $start = $request->start ?? 0;
        $length = $request->length ?? 10;
        $students = $query->skip($start)->take($length)->get();

        // Prepare student details
        $studentDetails = $students->map(function ($student) use ($submittedAssignments) {
            $assignmentInfo = $submittedAssignments->firstWhere('en_no', $student->enrollment_no);
            
            return [
                'en_no' => $student->enrollment_no,
                'name' => $student->name,
                'created_at' => $assignmentInfo ? $assignmentInfo->created_at->format('d-m-Y h:i A') : "N/A",
                'submitted' => $assignmentInfo ? '<span class="badge badge-success">done</span>' : '<span class="badge badge-warning">pending</span>',
            ];
        })->toArray();

        // Return response with filtered data
        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $studentDetails,
        ]);
    }
}
