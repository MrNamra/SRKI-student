@extends('layouts.app')
@section('main')
    <section class="content-header d-flex align-items-center justify-content-center" style="min-height: 100vh;">
        <!-- Horizontal Form -->
        <div class="card card-info w-50">
            <div class="card-header text-center">
                <h3 class="card-title">Login</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form id="login" class="form-horizontal" method="post">
                @csrf
                <div class="card-body">
                    <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-4 col-12 col-form-label text-left">Enrollment No.</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="en_no" name="enrollment_no" placeholder="Enrollment No...">
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-info">Sign in</button>
                    <button type="reset" class="btn btn-default">Cancel</button>
                </div>
                <!-- /.card-footer -->
            </form>
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
        $("#login").on('submit', function(e){
            e.preventDefault();
            $('.btn-info').attr('disabled', 'disabled');
            if($("#en_no").val() == ""){
                Toast.fire({
                    icon: 'warning',
                    title: "Enrollment No. is required!"
                })
            }
            else if($("#en_no").val().length != 11){
                Toast.fire({
                    icon: 'error',
                    title: "Enrollment No. is not valide!"
                })
            }else{
                var formData = new FormData(this);
                $.ajax({
                    url: "{{ route('stuLogin') }}",
                    processData: false,
                    contentType: false,
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        console.log(response);
                        if(response.status){
                            Toast.fire({
                                icon: 'success',
                                title: "Login Successfully!"
                            })
                            setTimeout(() => {
                                location.reload();
                            }, 1000);
                        } else{
                            Toast.fire({
                                icon: 'error',
                                title: "Login fail!"
                            })
                        }
                    },
                    error: function(err) {
                        console.log(err);
                        Toast.fire({
                            icon: 'error',
                            title: err.responseJSON.message
                        })
                    }
                });
            }
            setTimeout(function(){
                $('.btn-info').removeAttr('disabled');
            }, 2000)
        })
    });
</script>
@endsection