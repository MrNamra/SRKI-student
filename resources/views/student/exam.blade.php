@extends('layouts.app')

@section('main')
<section class="content-header d-flex align-items-center justify-content-center" style="min-height: 90vh; background-image: url('{{ url('/dist/img/background.jpg') }}'); background-size: cover; background-position: center;">
    <div class="lockscreen-wrapper" style="background-color: rgba(255, 255, 255, 0.9); border-radius: 15px; padding: 30px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);">
        <center>
            <h1 class="text-primary" style="font-weight: bold;">Welcome to the SRKI Exam Portal</h1>
        </center>
        
        <!-- START LOCK SCREEN ITEM -->
        <div class="lockscreen-item text-center">
            <div class="lockscreen-image">
                <img src="{{ url('/dist/img/man.png') }}" alt="User Image" style="border-radius: 50%; border: 2px solid #007bff;">
            </div>
            
            <form id="login" method="post" class="lockscreen-credentials">
                @csrf
                <div class="input-group mb-3" style="position: relative;">
                    <input type="text" id="en_no" name="enrollment_no" maxlength="11" class="form-control" placeholder="Enrollment No." required style="border-radius: 20px; border: 1px solid #007bff; padding-right: 50px;">
                    <button type="submit" class="btn" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background-color: #007bff; border-radius: 20px; border: none;">
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </form>
        </div>

        <div class="help-block text-center mb-3">
            <small class="text-muted">Enter your enrollment number to access the exam dashboard</small>
        </div>
        
        <div class="lockscreen-footer text-center">
            <strong>Developed By <a target="_blank" href="https://instagram.com/oye_namu" style="color: #007bff;">Namra Ramsha</a>.</strong><br>
            <small>All rights reserved.</small>
        </div>
    </div>
</section>
@endsection
