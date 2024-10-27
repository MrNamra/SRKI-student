<?php

namespace App\Repositories;

use App\Models\TimeTable;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
//use Your Model

/**
 * Class TimeTableRepository.
 */
class TimeTableRepository extends BaseRepository
{
    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return TimeTable::class;
    }

    public function UpdateOrCreate($data){
        return TimeTable::UpdateOrCreate(['id' => $data['id']], $data);
    }
    public function TimeTableList($request){
        $searchValue = $request->input('search.value');
    
        // Query with relationships
        $query = TimeTable::with('course')->with('subject')->orderby('StartDate', 'desc');
    
        // Apply search filter
        if ($searchValue) {
            $query->where(function($q) use ($searchValue) {
                $q->where('day', 'LIKE', "%{$searchValue}%")
                    ->orWhere('sem', 'LIKE', "%{$searchValue}%")
                    ->orWhere('div', 'LIKE', "%{$searchValue}%")
                    ->orWhere('StartDate', 'LIKE', "%{$searchValue}%")
                    ->orWhere('EndDate', 'LIKE', "%{$searchValue}%")
                    ->orWhereHas('subject', function($q) use ($searchValue) {
                        $q->where('name', 'LIKE', "%{$searchValue}%")
                        ->orWhere('subject_code', 'LIKE', "%{$searchValue}%");
                    })
                    ->orWhereHas('course', function($q) use ($searchValue) {
                        $q->where('name', 'LIKE', "%{$searchValue}%");
                    });
            });
        }
    
        // Total records before filtering
        $totalRecords = TimeTable::count();
    
        // Total records after filtering
        $filteredRecords = $query->count();
    
        // Pagination parameters
        $perPage = $request->input('length', 10); // Default to 10
        $currentPage = ($request->input('start', 0) / $perPage) + 1;
    
        // Get paginated data
        $timeTable = $query->paginate($perPage, ['*'], 'page', $currentPage);
    
        // Prepare response data directly
        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => collect($timeTable->items())->map(function($tt) {
                return [
                    'id' => $tt->id,
                    'day' => $tt->day,
                    'div' => $tt->div,
                    'sem' => $tt->sem,
                    'course_name' => $tt->course->name,
                    'subject_name' => $tt->subject->name,
                    'time' => date('m/d/Y h:i A', strtotime($tt->StartDate)).' - '.date('m/d/Y h:i A', strtotime($tt->EndDate)),
                ];
            }),
        ]);
    }
}
