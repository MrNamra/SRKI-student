@php $sub = \App\Models\Subject::select('name')->find(session('lab')->sub_id)->name; @endphp 
@extends('layouts.app')
@section('main')
    <style>
        .card {
            border-radius: 15px;
        }

        .card-header {
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
        }

        .btn-light {
            background-color: #f8f9fa;
            color: #007bff;
        }

        .btn-light:hover {
            background-color: #e2e6ea;
        }

        .img-thumbnail {
            border: 2px solid #007bff;
        }

        .dropzone {
            border: 2px dashed #007bff;
            /* Primary border color */
            border-radius: 10px;
            background: #f9f9f9;
            padding: 20px;
            transition: border-color 0.3s ease;
        }

        .dropzone.dz-clickable:hover {
            border-color: #0056b3;
            /* Darker shade on hover */
        }

        .dropzone .dz-message {
            font-size: 1.2em;
            color: #6c757d;
            /* Secondary text color */
        }

        .dropzone .dz-message h4 {
            font-weight: 600;
        }

        .dropzone .dz-message p {
            font-size: 0.9em;
        }
    </style>
    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <h3>Listed all assignment of 
                    {{$sub}} Subeject</h3>
                <div class="col-md-12 mt-5">
                    @php
                        $i=1;
                    @endphp
                    @foreach ($results as $result)
                        @if(session('lab')->id != $result->id)
                            <div class="card @if(!empty($result->assignment)) card-success @else card-danger @endif collapsed-card">
                                <div class="card-header" data-card-widget="collapse">
                                    <h3 class="card-title">{{$i++.": ".$result->title}}</h3>

                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                                class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                    <!-- /.card-tools -->
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    @if(!empty($result->assignment))
                                        hurrayy! Your this assignment is successfully submitted
                                        <div class="float-right">
                                            <a href="{{ route('download.assignment', $result->assignment->id) }}" target="_blank" data-id="{{ $result->assignment->id }}" class="btn btn-light mb-3 Download">
                                                <i class="fas fa-download"></i> Download File
                                            </a>
                                        </div>
                                    @else
                                        You have chance to handin this assignment (Devloper currently working on it)
                                        <div class="float-right">
                                            <input type="file" name="file" id="file-{{$result->id}}" data-id="{{$result->id}}" class="d-none file">
                                            <a href="#" data-id="{{$result->id}}" class="btn btn-light mb-3 upload">
                                                <i class="fas fa-upload"></i> Upload File
                                            </a>
                                            <div class="progress d-none">
                                                <div id="progress-bar" class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <!-- /.card-body -->
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endsection
@section('js')
    <script src="{{ url('plugins/dropzone/min/CFdropzone.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            })
            $(".upload").on('click', function() {
                var id = $(this).data('id');
                $("#file-" + id).click();
            })
            $('.file').on('change', function() {
                var id = $(this).data('id');
                var file = $(this)[0].files[0];
                var formData = new FormData();
                formData.append('file', file);
                formData.append('id', id);
                formData.append('_token', "{{csrf_token()}}");
                $.ajax({
                    url: '{{ route("upload.assignment") }}',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    xhr: function() {
                        var xhr = new window.XMLHttpRequest();
                        
                        // Upload progress event
                        xhr.upload.addEventListener("progress", function(evt) {
                            if (evt.lengthComputable) {
                                var percentComplete = evt.loaded / evt.total;
                                percentComplete = Math.round(percentComplete * 100);
                                
                                // Update progress bar (replace with your progress handling)
                                $(".progress").removeClass("d-none");
                                $("#progress-bar").css("width", percentComplete + "%").attr("aria-valuenow", percentComplete).text(percentComplete + "%");
                            }
                        }, false);
                        
                        return xhr;
                    },
                    success: function(response) {
                        Toast.fire({
                            icon: 'success',
                            title: "Submited Successfully!"
                        })
                        $(".progress").removeClass("d-none");
                        setTimeout(() => {
                            location.reload();
                        }, 2000);
                    },
                    error: function(err) {
                        $("#submit-all").removeAttr('disabled');
                        console.log(err);
                        Toast.fire({
                            icon: 'error',
                            title: err.responseJSON.error ? err.responseJSON.error : err
                                .responseJSON.message
                        })
                    }
                });
            })
        });
    </script>
@endsection
