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
                        {{-- @dd($result)ś --}}
                        <div class="card @if(!empty($result->assignment)) card-success @else card-danger @endif collapsed-card">
                            <div class="card-header">
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
                                        <a href="" data-id="{{ $result->assignment->id }}" class="btn btn-light mb-3" id="Download" >
                                            <i class="fas fa-download"></i> Download File
                                        </a>
                                    </div>
                                @else
                                    You have chance to handin this assignment (Devloper currently working on it)
                                @endif
                            </div>
                            <!-- /.card-body -->
                        </div>
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
            $('#Download').on('click', function(event) {
                event.preventDefault();
                $(this).data('id');

                $.ajax({
                    url: '',
                    type: 'POST',
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        Toast.fire({
                            icon: 'success',
                            title: "Submited Successfully!"
                        })
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
            });
        });
    </script>
@endsection
