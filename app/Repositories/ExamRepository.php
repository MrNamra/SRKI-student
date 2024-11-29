<?php

namespace App\Repositories;

use App\Models\Exam;
use App\Models\ExamInfo;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
//use Your Model

/**
 * Class ExamRepository.
 */
class ExamRepository extends BaseRepository
{
    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return Exam::class;
    }
    public function CreteOrUpdate($request) {
        Exam::updateOrCreate(["id" => $request['id']], $request);
    }
    public function getExamStudnetsOrUpadte($request, $id = null) {
        $exam = Exam::find($id);
        if (!$exam) {
            return response()->json(['error' => 'Exam not found'], 404);
        }

        // Fetch submitted assignments
        $submittedExams = ExamInfo::where('exam_id', $exam->id)->get();

        // Build the query for all students
        $query = Student::where('div', $exam->div)
                        ->where('sem', $exam->subject->sem);

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
        $studentDetails = $students->map(function ($student) use ($submittedExams) {
            $examInfo = $submittedExams->firstWhere('en_no', $student->enrollment_no);
            
            return [
                'en_no' => $student->enrollment_no,
                'name' => $student->name,
                'created_at' => $examInfo ? $examInfo->created_at->format('d-m-Y h:i A') : "N/A",
                'submitted' => $examInfo ? '<span class="badge badge-success">done</span>' : '<span class="badge badge-warning">pending</span>',
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
    public function distory($id) {
        $exam = Exam::find($id);
        if (!empty($exam->file_path) && Storage::disk('public')->exists($exam->file_path)) {
            Storage::disk('public')->delete($exam->file_path);
        }
        $exam->delete();
    }
    public function login($enno) {
        $student = Student::where('enrollment_no', $enno)->first();
        if (empty($student)) {
            return ['error' => true, 'message' => 'Enrollment Number is invalide!'];
        }
        $ip = (array_key_exists('HTTP_CLIENT_IP', $_SERVER))
        ? $_SERVER['HTTP_CLIENT_IP']
        : (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER) 
        ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR']);
        $exam = Exam::join('subjects', 'exams.sub_id', '=', 'subjects.id')
                        ->join('students', 'subjects.course_id', '=', 'students.course_id')
                        ->where('students.enrollment_no', $enno)
                        ->where('exams.div', '=', DB::raw('students.div'))
                        ->where('exams.StartTime', '<=', now())
                        ->where('exams.EndTime', '>=', now())
                        ->select('exams.*', 'subjects.*', 'subjects.name AS subject_name', 'students.*')
                        ->first();
        if(!$exam) {
            return ['error' => true, 'message' => 'Exam Is Not Started!'];
        }
        Auth::guard('student')->login($student);
        session()->put('exam', $exam);

        return true;
    }
    public function storeResponse($file, $ip, $id = null) {
        $session = session('exam');
        $student = Auth::guard('student')->user();
        $exam = Exam::find($session->id);
        $customFileName = $session->title . '.' . $file->getClientOriginalExtension();
        if($exam->EndTime < now()) {
            return ['error' => true, 'message' => 'Exam Is Over!'];
        }
        $path = "assignments/".$session->course->name.'/'.$session->sem.'/'.$session->div.'/'.$student->enrollment_no.'/exam/'.$session->subject_name.'/';
        // Check if an assignment already exists for this student
        $exam_id = $id ?? $session->id;
        $exam = ExamInfo::where('exam_id', $exam_id)
            ->where('en_no', $student->enrollment_no)
            ->first();

        if ($exam) {
            // If the exam exists, get the old file path and its extension
            $oldFilePath = $exam->file_path;
            $oldFileExtension = pathinfo($oldFilePath, PATHINFO_EXTENSION);
            $newFileExtension = $file->getClientOriginalExtension();

            // Check if the extensions are the same
            if ($oldFileExtension === $newFileExtension) {
                return $file->storeAs($path, $customFileName, 'public');
            } else {
                // If the extension is different, delete the old file
                if (Storage::disk('public')->exists($oldFilePath)) {
                    Storage::disk('public')->delete($oldFilePath);
                }

                // Create a new record and store the new file
                $exam->update([
                    'file_path' => $path.$customFileName,
                    'ip' => $ip
                ]);
                return $file->storeAs($path, $customFileName, 'public');
            }
        } else {
            $exam = exam::find($exam_id);
            // If it doesn't exist, create a new record
            $exam = new examInfo();
            $exam->id = $student->id;
            $exam->en_no = $student->enrollment_no;
            $exam->exam_id = $exam_id;
            $exam->file_path = $path.$customFileName;
            $exam->ip = $ip;
            $exam->save();

            // Store the new file
            return $file->storeAs($path, $customFileName, 'public');
        }
    }
    public function getExamsCandidets($request, $id) {
        $data = Exam::with('subject')->find($id);
        if (!$data) {
            return response()->json(['error' => 'Exam not found'], 404);
        }
    
        // Fetch submitted exam info
        $submittedExamInfo = ExamInfo::where('exam_id', $data->id)->get();
    
        // Build the query for all students
        $query = Student::where('div', $data->div)
                        ->where('sem', $data->subject->sem);
    
        // Apply search filter if present
        if (!empty($request->search['en_no'])) {
            $query->where('enrollment_no', 'like', "%{$request->search['en_no']}%");
        }
        if (!empty($request->search['name'])) {
            $query->where('name', 'like', "%{$request->search['name']}%");
        }
    
        // Get total records before applying pagination
        $totalRecords = $query->count();
    
        // Apply pagination
        $start = $request->start ?? 0;
        $length = $request->length ?? 10;
        $students = $query->skip($start)->take($length)->get();
    
        // Prepare student details
        $studentDetails = $students->map(function ($student) use ($submittedExamInfo) {
            $examInfo = $submittedExamInfo->firstWhere('en_no', $student->enrollment_no);
            
            // Determine the status based on marks and file_path
            if ($examInfo) {
                $marks = $examInfo->marks;
                $filePath = $examInfo->file_path; // Assuming this field exists in ExamInfo
    
                if ($marks > 0 && !is_null($filePath)) {
                    $submittedStatus = '<span class="badge badge-success">Submitted</span>';
                } elseif ($marks == 0) {
                    $submittedStatus = '<span class="badge badge-warning">Pending</span>';
                } else {
                    $submittedStatus = '<span class="badge badge-warning">Wait for file</span>';
                }
            } else {
                $submittedStatus = '<span class="badge badge-warning">Pending</span>'; // No exam info means pending
            }
    
            return [
                'en_no' => $student->enrollment_no,
                'name' => $student->name,
                'marks' => $examInfo ? $examInfo->marks : "N/A",
                'submitted' => $submittedStatus,
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
    public function updateCandidetsMarks($request) {
        $exam = ExamInfo::where('exam_id', $request->id)
                        ->where('en_no', $request->en_no);
        if($exam->first()){
            $exam->update(['marks' => $request->marks]);
            return response()->json(['status' => true, 'message' => 'Marks updated successfully']);
        }
        ExamInfo::create([
            'exam_id' => $request->id,
            'en_no' => $request->en_no,
            'marks' => $request->marks
        ]);
        return response()->json(['status' => true, 'message' => 'Marks updated successfully Wait for File']);
    }
}
