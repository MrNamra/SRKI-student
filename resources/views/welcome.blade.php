@extends('layouts.app')
@section('main')
            <h1>
                SRKI
            </h1>
            <form id="stud-from" method="POST">
                @csrf
                <label>Enrollment No.</label>
                <br>
                <input type="text" id="enroll" name="enroll" required>
                <input type="submit">
            </form>
@endsection