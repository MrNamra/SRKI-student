<?php

namespace App\Interface;

interface CourceRepositoryInterface
{
    public function createOrUpdate($data, $id = null);
}
