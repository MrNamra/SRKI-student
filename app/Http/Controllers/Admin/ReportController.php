<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AssignmentInfo;
use App\Models\Cource;
use App\Models\LabSchedule;
use App\Models\Student;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index() {
        $courses = Cource::get();
        return view('admin.report', ['courses' => $courses]);
    }
    public function GenerateReport(Request $request) {
        // Step 1: Validate the input
        $request->validate([
            'course_id' => 'required|integer',
            'sem' => 'required|integer',
            'subject_id' => 'required|integer',
            'div' => 'required|string',
        ]);
    
        $course_id = $request->course_id;
        $sem = $request->sem;
        $subject_id = $request->subject_id;
        $div = $request->div;
    
        // Step 2: Fetch labs within the last 6 months
        $labs = LabSchedule::where('sub_id', $subject_id)
            ->where('div', $div)
            ->where('StartTime', '>=', now()->subMonths(6))
            ->get();
    
        // Step 3: Fetch students based on course_id, sem, and div
        $students = Student::with(['assignment' => function ($query) use ($labs) {
                $query->whereIn('assingment_id', $labs->pluck('id'));
            }])
            ->where('course_id', $course_id)
            ->where('sem', $sem)
            ->where('div', $div)
            ->get();
    
        // Step 4: Prepare the response data
        $response = [];
        $assignmentCount = $labs->count();
    
        foreach ($students as $student) {
            $studentData = [
                'en_no' => $student->enrollment_no,
            ];
            
            $submittedAssignments = 0;
            foreach ($labs as $index => $lab) {
                $assignment = $student->assignment->where('assingment_id', $lab->id)->first();
                $studentData['ass' . ($index + 1)] = $assignment ? $assignment->created_at->format('d-m-Y') : 'N/A';
                if ($assignment) {
                    $submittedAssignments++;
                }
            }
    
            // Total column for submitted/total assignments
            $studentData['total'] = "$submittedAssignments/$assignmentCount";
    
            $response[] = $studentData;
        }
    
        // Step 5: Send response for DataTables
        return response()->json([
            'data' => $response,
            'columns' => array_merge(
                [['title' => 'En_no']],
                array_map(fn($i) => ['title' => "ass$i"], range(1, $assignmentCount)),
                [['title' => 'Total']]
            ),
        ]);
    }
}
