<?php

namespace App\Interface;

interface LabRepositoryInterface
{
    public function UpdateOrCreate($request);
    public function distory($id);
}
