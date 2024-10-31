@extends('layouts.app')

@section('main')
<section class="content-header d-flex align-items-center justify-content-center" style="min-height: 90vh; background-size: cover; background-position: center;">
    <div class="lockscreen-wrapper" style="background-color: rgba(255, 255, 255, 0.9); border-radius: 15px; padding: 30px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);">
        <center>
            <h1 class="text-primary" style="font-weight: bold;">Welcome to the SRKI Exam Portal</h1>
        </center>
        
        <!-- START LOCK SCREEN ITEM -->
        <div class="lockscreen-item text-center" style="margin: 15px 0 0 0;">
            
            <form id="login" method="post" class="lockscreen-credentials">
                @csrf
                <div class="input-group mb-3" style="position: relative;">
                    <input type="text" id="en_no" name="enrollment_no" maxlength="11" class="form-control" placeholder="Enrollment No." required style="border-radius: 20px; border: 1px solid #007bff; padding: 0px 40px 4px 40px;">
                    <div class="lockscreen-image" style="position: absolute; left: 1px; top: 50%; transform: translateY(-50%); background-color: #007bff; border-radius: 20px; border: none; height: 35px; width: 35px;">
                        <img src="{{ url('/dist/img/man.png') }}" alt="User Image" style="position: absolute; left: 0px; top: 48%; transform: translateY(-50%); border: none; height: 35px; width: 35px;">
                    </div>
                    <button type="submit" class="btn" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background-color: #007bff; border-radius: 20px; border: none; z-index: 1;"> <!-- Added z-index -->
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
@section('js')
<script>
    $(document).ready(function() {
        Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        })
        $("#login").submit(function(e) {
            e.preventDefault();
            $('.btn').attr('disabled', 'disabled');
            $.ajax({
                type: "POST",
                url: "{{ route('student.exam.login') }}",
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        Toast.fire({
                            icon: 'success',
                            title: response.message,
                        });
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: response.message,
                        });
                    }
                },
                error: function(err) {
                    console.log(err);
                    $('.btn').removeAttr('disabled');
                    Toast.fire({
                        icon: 'error',
                        title: err.responseJSON.message || "Error fetching data"
                    });
                }
            })
        });
    });
</script>
@endsection