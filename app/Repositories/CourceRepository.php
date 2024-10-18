<?php

namespace App\Repositories;

use App\Interface\CourceRepositoryInterface;
use App\Models\Cource;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;

class CourceRepository extends BaseRepository implements CourceRepositoryInterface
{
    // protected $Course;
    // public function __construct(Cource $cource)
    // {
    //     $this->Course = $cource;
    // }
    public function model()
    {
        return Cource::class;
    }
    /**
     * @return string
     *  Return the model
     */
    public function createOrUpdate($data, $id = null)
    {
        return Cource::UpdateOrCreate(['id' => $id], $data);
    }
}
