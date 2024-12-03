@extends('layouts.admin_app')
@section('main')
<section class="content-header text-center">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-12">
          <h1>Welcome</h1>
          <hr style="margin: 0 5% 0 5%;background-color: black;">
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>
<section class="content">
    <form class="upload-form" enctype="multipart/form-data" method="post">
        @csrf
        <div class="card">
            <!-- /.card -->
            <div class="row col-11 mt-3 ml-3">
                <div class="col-9 mb-3">
                    <label>Asignment Id</label>
                    @php
                        $labs = DB::table('lab_schedules')->latest()->get();
                    @endphp
                    <select class="form-control select2 col-12" name="lab_id" required>
                        <option selected disabled>Select Assignment</option>
                        @foreach ($labs as $lab)
                            <option value="{{$lab->id}}">{{$lab->title}}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-3">
                </div>
                <div class="col-3">
                    <input class="form-control col-12" name="en_no" placeholder="Enrollment No">
                </div>
            </div>
            <div class="row col-11 mt-3 ml-3">
                <div class="col-12">
                    <div class="form-group">
                        <label>Start & End Date and time range:</label><small>(DD/MM/YYYY HH:MM)</small>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="far fa-clock"></i></span>
                            </div>
                            <input type="datetime-local" class="form-control float-right reservationtime" name="date" id="reservationtime">
                        </div>
                        <!-- /.input group -->
                    </div>
                </div>
            </div>
            <div class="col-11 mt-3 mb-2" style="margin: 0 5% 0 5%;">
                <input type="submit" class="btn btn-primary col-12" value="Upload & Schedule"></input>
            </div>
        </div>
    </form>
</section>
@endsection
@section('js')
<!-- date-range-picker -->
<script src="{{url('/plugins/daterangepicker/daterangepicker.js')}}"></script>
<!-- dropzonejs -->
<script src="{{url('/plugins/dropzone/min/dropzone.min.js')}}"></script>
<!-- Summernote -->
<script src="{{url('/plugins/summernote/summernote-bs4.min.js')}}"></script>
<link type="text/css" rel="stylesheet" href="{{url('/plugins/jsgrid/jsgrid-theme.min.css')}}"></link>
<link type="text/css" rel="stylesheet" href="{{url('/plugins/jsgrid/jsgrid.min.css')}}"></link>
<script type="text/javascript" src="{{url('/plugins/jsgrid/jsgrid.min.js')}}"></script>
<script>
    $(document).ready(function() {
        $('.upload-form')[0].reset();
        // Summernote
        Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        })
        // $('.reservationtime').daterangepicker({
        //     timePicker: true,
        //     timePickerIncrement: 10,
        //     locale: {
        //         format: 'MM/DD/YYYY hh:mm A'
        //     }
        // })
        $('.upload-form').on('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            $.ajax({
                url: '{{ route("submitDone") }}',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    console.log(response);
                    if(response.status){
                        Toast.fire({
                            icon: 'success',
                            title: "Uploaded Successfully!"
                        })
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    } else{
                        Toast.fire({
                            icon: 'error',
                            title: response.message || "Uploading data fail!"
                        })
                    }
                },
                error: function(err) {
                    console.log(err);
                    if (err.status === 422) {
                        // Validation error
                        let errors = err.responseJSON.errors;
                        let errorMessages = '';
                        for (let field in errors) {
                            if (errors.hasOwnProperty(field)) {
                                errorMessages += errors[field].join('<br>') + '<br>';
                            }
                        }
                        Toast.fire({
                            icon: 'error',
                            title: 'Validation Error!',
                            html: errorMessages // Displaying all validation errors
                        });
                    } else {
                        // Other errors
                        Toast.fire({
                            icon: 'error',
                            title: err.responseJSON.message || 'An error occurred'
                        });
                    }
                }
            })
        });
    });

</script>
@endsection