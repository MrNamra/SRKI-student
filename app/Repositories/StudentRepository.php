<?php

namespace App\Repositories;

use App\Interface\StudentRepositoryInterface;
use App\Models\Student;
use Exception;
use Illuminate\Support\Facades\DB;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;

/**
 * Class StudentRepository.
 */
class StudentRepository extends BaseRepository implements StudentRepositoryInterface
{
    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return Student::class;
    }

    public function store($request){
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
            try {
                Student::insert($records);
                DB::commit();
            } catch (Exception $e) {
                DB::rollBack();
                throw new Exception('Error saving data: ' . $e->getMessage());
            }
        } else {
            throw new Exception('No file uploaded');
        }
    }
}
