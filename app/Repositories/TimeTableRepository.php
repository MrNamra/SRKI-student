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
}
