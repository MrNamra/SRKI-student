<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cource;
use App\Repositories\AssginamnetRepository;
use App\Repositories\CourceRepository;
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
            $cources = Cource::get();
            return view('admin.assignment', ['cources' => $cources]);
        }catch(Exception $ex){
            dd($ex);
        }
    }
}
