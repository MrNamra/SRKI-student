<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cource;
use App\Repositories\AssginamnetRepository;
use App\Repositories\CourceRepository;
use App\Repositories\StudentRepository;
use Exception;
use Illuminate\Http\Request;

class AssginamnetController extends Controller
{
    // private $assignamnetRepo;
    // public function __construct(AssginamnetRepository $assignment)
    // {
    //     $this->assignamnetRepo = $assignment;
    // }
    public function index(){
        try{
            $cources = Cource::get();
            return view('admin.assignment', ['cources' => $cources]);
        }catch(Exception $ex){
            dd($ex);
        }
    }
    // public function store(Request $request){
    //     $request->validate([
    //         'file' => 'required',
    //         'cource_id' => 'required|numeric',
    //         'sem' => 'required|numeric',
    //         'subject_id' => 'required|numeric',
    //     ]);
    //     try{
    //         // $this->assignamnetRepo->store($request);
    //         return redirect()->back();
    //     }catch(Exception $ex){
    //         dd($ex);
    //     }
    // }
}
