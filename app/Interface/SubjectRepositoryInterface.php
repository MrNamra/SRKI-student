<?php

namespace App\Interface;

interface SubjectRepositoryInterface
{
    public function createOrUpdate($data, $id);

    public function remove($id);
}
