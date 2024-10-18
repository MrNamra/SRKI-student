<?php

namespace App\Repositories;

use App\Interface\AssignmentRepositoryInterface;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
//use Your Model

/**
 * Class AssginamnetRepository.
 */
class AssginamnetRepository extends BaseRepository implements AssignmentRepositoryInterface
{
    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        //return YourModel::class;
    }
}
