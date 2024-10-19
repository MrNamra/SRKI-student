<?php

namespace App\Repositories;

use App\Interface\SubjectRepositoryInterface;
use App\Models\Subject;
use Exception;
use Illuminate\Support\Facades\DB;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
//use Your Model

/**
 * Class SubjectRepository.
 */
class SubjectRepository extends BaseRepository implements SubjectRepositoryInterface
{
    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return Subject::class;
    }

    public function createOrUpdate($data, $id = null)
    {
        DB::transaction(function () use ($data, $id) {
            return Subject::updateOrCreate(['id' => $id], $data);
        });
        return true;
    }
    public function remove($id)
    {
        try {
            Subject::destroy($id);
            return true;
        }catch(Exception $e) {
            return false;
        }
    }
}
