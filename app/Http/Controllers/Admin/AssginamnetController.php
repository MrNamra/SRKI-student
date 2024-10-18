<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\AssginamnetRepository;
use App\Repositories\CourceRepository;
use App\Repositories\StudentRepository;
use Exception;
use Illuminate\Http\Request;

class AssginamnetController extends Controller
{
    // private $courceRepo, $assignamnetRepo, $studentRepo;
    // public function __construct(CourceRepository $cource, AssginamnetRepository $assignment, StudentRepository $student)
    // {
    //     $this->courceRepo = $cource;
    //     $this->assignamnetRepo = $assignment;
    //     $this->studentRepo = $student;
    // }
    public function index(){
        try{
            return view('admin.assignment');
        }catch(Exception $ex){
            dd($ex);
        }
    }
}
