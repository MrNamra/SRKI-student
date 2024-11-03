<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cource;
use App\Models\course;
use App\Repositories\AssginamnetRepository;
use App\Repositories\courseRepository;
use App\Repositories\StudentRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class AssginamnetController extends Controller
{
    private $assignamnetRepo;
    public function __construct(AssginamnetRepository $assignment)
    {
        $this->assignamnetRepo = $assignment;
    }
    public function index(){
        try{
            $courses = Cource::get();
            return view('admin.assignment', ['courses' => $courses]);
        }catch(Exception $ex){
            dd($ex);
        }
    }
}
