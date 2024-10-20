<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentsController extends Controller
{
    public function login(Request $request) {
        try{
            $student = Student::where('enrollment_no', $request->enroll)->first();
            if (!$student) {
                return response()->json(['error' => 'fail', 'message' => 'Invalide EmromentID!'], 404);
            }
            $ip = (array_key_exists('HTTP_CLIENT_IP', $_SERVER))
            ? $_SERVER['HTTP_CLIENT_IP']
            : (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER) 
            ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR']);
            if($ip != $student->ip) {
                return response()->json(['error' => 'fail', 'message' => 'Access Deny!'], 401);
            }
            Auth::login($student);
            session()->put('student', $student);
            return response()->json(['success' => 'success', 'message' => 'Access Granted!'], 200);
        } catch (\Exception $e){
            return response()->json(['error' => $e->getMessage()], 401);
        }     
    }
}
