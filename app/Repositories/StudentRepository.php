<?php

namespace App\Repositories;

use App\Interface\StudentRepositoryInterface;
use App\Models\AssignmentInfo;
use App\Models\Cource;
use App\Models\LabSchedule;
use App\Models\Student;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;

/**
 * Class StudentRepository.
 */
class StudentRepository extends BaseRepository implements StudentRepositoryInterface
{
    public function model()
    {
        return Student::class;
    }
    public function store($request){
        DB::beginTransaction();
        try {
            if ($request->file('file')) {
                $file = $request->file('file');
                $path = $file->store('uploads/csv', 'public');
                
                $data = array_map('str_getcsv', file(storage_path('app/public/uploads/csv/' . basename($path))));
                $header = $data[0];
                unset($data[0]);
                $records = [];

                foreach ($data as $row) {
                    $course_id = Cource::where('name', $row['3'])->first();
                    if (!$course_id) {
                        throw new Exception('Invalid course name: '. $row['3']);
                    }
                    $records[] = [
                        'enrollment_no' => $row[0],
                        'ip' => $row[1],
                        'name' => $row[2],
                        'course_id' => $course_id->id,
                        'sem' => $row[4],
                        'div' => $row[5],
                    ];
                }

                    Student::insert($records);
                    if (Storage::disk('public')->exists($path)) {
                        Storage::disk('public')->delete($path);
                    }
                    DB::commit();
                } else {
                    throw new Exception('No file uploaded');
                }
            } catch (Exception $e) {
                DB::rollBack();
                if (Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
                throw new Exception('Error saving data: ' . $e->getMessage());
            }
    }
    public function getSudentsList($request) {
        $searchValue = $request->input('search.value');
    
        // Query with relationships
        $query = Student::with('course');
    
        // Apply search filter
        if ($searchValue) {
            $query->where(function($q) use ($searchValue) {
                $q->where('name', 'LIKE', "%{$searchValue}%")
                    ->orWhere('sem', 'LIKE', "%{$searchValue}%")
                    ->orWhere('ip', 'LIKE', "%{$searchValue}%")
                    ->orWhere('div', 'LIKE', "%{$searchValue}%")
                    ->orWhere('enrollment_no', 'LIKE', "%{$searchValue}%")
                    ->orWhereHas('course', function($q) use ($searchValue) {
                        $q->where('name', 'LIKE', "%{$searchValue}%");
                    });
            });
        }
    
        // Total records before filtering
        $totalRecords = Student::count();
    
        // Total records after filtering
        $filteredRecords = $query->count();
    
        // Pagination parameters
        $perPage = $request->input('length', 10); // Default to 10
        $currentPage = ($request->input('start', 0) / $perPage) + 1;
    
        // Get paginated data
        $students = $query->paginate($perPage, ['*'], 'page', $currentPage);
    
        // Prepare response data directly
        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => collect($students->items())->map(function($student) {
                return [
                    'enrollment_no' => $student->enrollment_no,
                    'ip' => $student->ip,
                    'name' => $student->name,
                    'course_name' => $student->course->name,
                    'sem' => $student->sem,
                    'div' => $student->div,
                ];
            }),
        ]);
    }
    public function getSudentById($id){
        return Student::where('enrollment_no', $id)->first();
    }
    public function deleteSudentById($id){
        return Student::where('enrollment_no', $id)->delete();
    }
    public function updateSudentById($request) {
        return Student::where('enrollment_no', $request->enrollment_no)->update($request->except(['enrollment_no', '_token']));
    }
    public function promoteSudents(){
        DB::beginTransaction();
        try {
            for($i = 8; $i >= 1; $i--){
                $students = Student::with('course')->with('assignment')->where('sem', $i)->get();
                if($students->isNotEmpty() && $students[0]->course->no_of_sem == $i){
                    foreach($students as $student){
                        foreach($student->assignment as $assignment){
                            $DirPath = 'assignments/'.$student->course->name.'/'.$i.'/'.$student->div.'/'.$student->enrollment_no;
                            if(Storage::disk('public')->exists($DirPath)){
                                Storage::deleteDirectory($DirPath);
                            }
                        }
                        $student->delete();
                    }
                }
                Student::with('course')->with('assignment')->where('sem', $i)->update(['sem' => $i + 1]);
            }
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Error saving data: ' . $e->getMessage());
        }
    }
    public function demoteSudents(){
        DB::beginTransaction();
        try {
            for($i = 1; $i <= 8; $i++){
                $students = Student::where('sem', $i)->update(['sem' => $i - 1]);
            }
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Error saving data: ' . $e->getMessage());
        }
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
        if($ip != $student->ip) {
            return ['error' => true, 'message' => 'Access Deny!'];
        }
        $lab = LabSchedule::join('subjects', 'lab_schedules.sub_id', '=', 'subjects.id')
                        ->join('students', 'subjects.course_id', '=', 'students.course_id')
                        ->where('students.enrollment_no', $enno)
                        ->where('lab_schedules.div', '=', DB::raw('students.div'))
                        ->where('lab_schedules.StartTime', '<=', now())
                        ->where('lab_schedules.EndTime', '>=', now())
                        ->select('lab_schedules.*', 'subjects.*', 'subjects.name AS subject_name', 'students.*')
                        ->first();
        if(!$lab) {
            return ['error' => true, 'message' => 'Lab Is Not Schedule!'];
        }
        Auth::guard('student')->login($student);
        session()->put('lab', $lab);

        return true;
    }
    public function submitAssignment($file) {
        $session = session('lab');
        $student = Auth::guard('student')->user();
        $customFileName = $session->title . '.' . $file->getClientOriginalExtension();
        $course = Cource::find($session->course_id)->name;
        $path = 'assignments/' . $course . '/' . $session->sem . '/' . $session->div . '/' . $student->enrollment_no . '/' . $session->subject_name;

        // Check if an assignment already exists for this student
        $assignment = AssignmentInfo::where('assingment_id', $session->id)
            ->where('en_no', $student->enrollment_no)
            ->first();

        if ($assignment) {
            // If the assignment exists, get the old file path and its extension
            $oldFilePath = $assignment->file_path;
            $oldFileExtension = pathinfo($oldFilePath, PATHINFO_EXTENSION);
            $newFileExtension = $file->getClientOriginalExtension();

            // Check if the extensions are the same
            if ($oldFileExtension === $newFileExtension) {
                // Overwrite the existing file
                return $file->storeAs($path, $customFileName, 'public');
            } else {
                // If the extension is different, delete the old file
                if (Storage::disk('public')->exists($oldFilePath)) {
                    Storage::disk('public')->delete($oldFilePath);
                }

                // Create a new record and store the new file
                $assignment->update(['file_path' => $path]);
                return $file->storeAs($path, $customFileName, 'public');
            }
        } else {
            // If it doesn't exist, create a new record
            $assignment = new AssignmentInfo();
            $assignment->en_no = $student->enrollment_no;
            $assignment->assingment_id = $session->id;
            $assignment->file_path = $path;
            $assignment->save();

            // Store the new file
            return $file->storeAs($path, $customFileName, 'public');
        }
    }
}
