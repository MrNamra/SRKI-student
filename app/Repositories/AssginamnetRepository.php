<?php

namespace App\Repositories;

use App\AssginamnetRepositoryInterface;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
//use Your Model

/**
 * Class AssginamnetRepository.
 */
class AssginamnetRepository extends BaseRepository implements AssginamnetRepositoryInterface
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
