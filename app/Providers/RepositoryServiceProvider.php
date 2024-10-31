<?php

namespace App\Providers;

use App\Interface\AssignmentRepositoryInterface;
use App\Interface\CourceRepositoryInterface;
use App\Interface\StudentRepositoryInterface;
use App\Interface\SubjectRepositoryInterface;
use Illuminate\Support\ServiceProvider;
use App\Repositories\AssginamnetRepository;
use App\Repositories\CourceRepository;
use App\Repositories\StudentRepository;
use App\Repositories\SubjectRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(AssignmentRepositoryInterface::class, AssginamnetRepository::class);
        $this->app->bind(CourceRepositoryInterface::class, CourceRepository::class);
        $this->app->bind(StudentRepositoryInterface::class, StudentRepository::class);
        $this->app->bind(SubjectRepositoryInterface::class, SubjectRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
