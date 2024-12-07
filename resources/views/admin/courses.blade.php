@extends('layouts.admin_app')
@section('main')
    {{-- @dd($streams) --}}
    <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div class="row mt-2">
                <div class="col-12">
                    <form method="POST" id="add-course">
                        @csrf
                        <div class="card card-default" style="padding: 20px;">
                            <label>Add Courses</label>
                            <div class="input-group mb-3 col-12">
                                <input type="text" class="form-control col-10" name="name" placeholder="BScIT" />
                                <input type="number" class="form-control col-2" min="1" max="10"
                                    name="no_of_sem" placeholder="Total Sem Of This Course" />
                                <div class="input-group-append">
                                    <br><button class="btn btn-primary" type="submit"><i class="fas fa-chart-bar"></i> Add
                                        Courses</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <hr>
                    <div class="card card-default" style="padding: 20px;">
                        <center>
                            <h3>Subject Add</h3>
                        </center>
                        <form method="POST" id="add-subject">
                            @csrf
                            <label for="stream">Select Stream</label>
                            <select id="stream" class="form-control select2" name="course_id"
                                data-placeholder="Select a Course">
                                <option value="" selected disabled>Select Stream</option>
                                @if ($streams)
                                    @foreach ($streams as $stream)
                                        <option value="{{ $stream->id }}" data-sem="{{ $stream->no_of_sem }}">{{ $stream->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <label for="sem">Select Subject For Sem</label>
                            <select id="sem" class="form-control" name="sem">
                            </select>
                            <label for="Subject">Add Subject</label>
                            <input type="text" id="Subject" class="form-control" name="name"
                                placeholder="Subject" />
                            <label for="Subject">Subject code</label>
                            <input type="text" id="Subject-code" class="form-control" name="subject_code"
                                placeholder="Subject Code" />
                            <button class="btn btn-success mt-2" type="submit"><i class="fas fa-book"></i> Add
                                Subject</button>
                        </form>
                    </div>
                </div>
            </div>
            <!-- ./col -->
            <div class="container mt-5">
                <table class="table table-bordered mb-5" id="dataTable">
                    <thead>
                        <tr class="thead-dark">
                            <th scope="col">#</th>
                            <th scope="col">Subject Name(code)</th>
                            <th scope="col">Course Name</th>
                            <th scope="col">Sem</th>
                            <th scope="col">Sub. Name</th>
                            <th scope="col"><i class="fas fa-cogs"></i></th>
                        </tr>
                    </thead>

                    <tbody>
                        {{-- @foreach ($subjects as $subject) --}}
                        <tr>
                            <th></th>
                            <td></td>
                            <td></td>
                            <td></td>
                            {{-- <th scope="row">{{ $subject->id }}</th>
                          <td>{{ $subject->name }}</td>
                          <td>{{ ($subject->year == '1')? 'FY' : (($subject->year == '2')? 'SY' : 'TY') }}</td>
                          <td>{{ $subject->name }}</td> --}}
                        </tr>
                        {{-- @endforeach --}}
                    </tbody>
                </table>
            </div>
        </div>
        <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <div class="modal fade" id="lodingModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="overlay">
                    <i class="fas fa-2x fa-sync fa-spin"></i>
                </div>
                <div class="modal-header">
                    <h4 class="modal-title">Edit Modal</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" id="edit-from">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="id" placeholder="Name" />
                        <label class="control-label">Course</label>
                        <select class="form-control select2" name="course_id" data-placeholder="Select a Corce">
                            @if ($streams)
                                @foreach ($streams as $stream)
                                    <option value="{{ $stream->id }}">{{ $stream->name }}</option>
                                @endforeach
                            @endif
                        </select>
                        <label class="control-label">Sem</label>
                        <select class="form-control" name="sem">
                            @for ($i = 1; $i <= 8; $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                        <label class="control-label">Sub. Name</label>
                        <input type="text" name="name" class="form-control" placeholder="Name" />
                        <label class="control-label">Sub. Code</label>
                        <input type="text" name="subject_code" class="form-control" placeholder="Subject Code" />
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
    {{-- Delete modal --}}
    <div class="modal fade" id="delete-modal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Delete?</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are You Sure Do You Want To Delete This?</p>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                    <button type="button" id="delete-modal-btn" class="btn btn-danger">Yup</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    {{-- Delete modal end --}}
@endsection
@section('js')
    <script>
        var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });

        $(document).ready(function() {
            setTimeout(function() {
                $('#dataTable_filter .form-control').attr('placeholder', 'Subject Name Only');
            }, 100)

            // for select2
            $('.select2').select2();

            $("#stream").on('change', function() {
                var sem = $(this).select2("data")[0].element.dataset.sem ?? 0;
                for (var i = 1; i <= sem; i++) {
                    $("#sem").append('<option value="' + i + '">' + i + '</option>');
                }
            })
            $('#dataTable').on('click', '.edit-data', function() {
                $('.overlay').show();
                $('#lodingModal').modal('show');
                var id = $(this).data('id');
                $.ajax({
                    url: '{{ route('getSubject') }}',
                    method: 'GET',
                    data: {
                        id: id
                    },
                    success: function(res) {
                        console.log(res);
                        $('.overlay').hide();
                        $('#edit-from').find('[name="id"]').val(res.id);
                        $('#edit-from').find('[name="name"]').val(res.name);
                        $('#edit-from').find('[name="sem"]').val(res.sem);
                        $('#edit-from').find('[name="subject_code"]').val(res.subject_code);
                        $('#edit-subject-form').modal('show');
                    },
                    error: function(err) {
                        console.log(err);
                        Toast.fire({
                            icon: 'error',
                            title: err.responseJSON.message
                        })
                    }
                })
            });
            $('#dataTable').on('click', '.delete-data', function() {
                $('#delete-modal').modal('show');
                var id = $(this).data('id');
                $('#delete-modal-btn').attr('data-id', id);
            });
            $('#dataTable').DataTable({
                serverSide: true,
                ordering: false,
                "lengthChange": false,
                "autoWidth": true,
                "responsive": true,
                ajax: {
                    url: '{{ route('getSubjects') }}',
                    dataSrc: 'data'
                },
                columns: [{
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    }, // Index column
                    {
                        data: 'subject_code'
                    },
                    {
                        data: 'cource_name'
                    },
                    {
                        data: 'sem'
                    },
                    {
                        data: 'name'
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return '<button class="btn btn-sm btn-primary edit-data" data-id="' +
                                data.id +
                                '">edit</button><br><button class="btn btn-sm btn-danger delete-data" data-id="' +
                                data.id + '">Delete</button>';
                        }
                    } // Action column
                ]
            });
            $('#add-course').on('submit', function(e) {
                e.preventDefault();
                Toast.fire({
                    icon: 'info',
                    title: "Request in process...",
                })
                var formData = new FormData(this);
                $.ajax({
                    url: "{{ route('add-course') }}",
                    processData: false,
                    contentType: false,
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        console.log(response);
                        if (response.status) {
                            Toast.fire({
                                icon: 'success',
                                title: "Added Successfully!"
                            })
                        } else {
                            Toast.fire({
                                icon: 'error',
                                title: "Adding data fail!"
                            })
                        }
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    },
                    error: function(err) {
                        console.log(err);
                        Toast.fire({
                            icon: 'error',
                            title: err.responseJSON.message
                        })
                    }
                });
            });
            $('#add-subject').on('submit', function(e) {
                e.preventDefault();
                Toast.fire({
                    icon: 'info',
                    title: "Request in process...",
                })
                var formData = new FormData(this);
                console.log(formData)
                $.ajax({
                    url: '{{ route('add-subject') }}',
                    processData: false,
                    contentType: false,
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        console.log(response);
                        Toast.fire({
                            icon: 'success',
                            title: "Added Successfully!"
                        })
                        location.reload();
                    },
                    error: function(err) {
                        console.log(err);
                        Toast.fire({
                            icon: 'error',
                            title: err.responseJSON.message
                        })
                    }
                });
            });
            $('#delete-modal-btn').on('click', function() {
                var id = $(this).data('id');
                $.ajax({
                    url: '{{ route('delete-subject') }}',
                    method: 'DELETE',
                    data: {
                        id: id,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        console.log(response);
                        if (response.status) {
                            Toast.fire({
                                icon: 'success',
                                title: "Deleted Successfully!"
                            })
                        } else {
                            Toast.fire({
                                icon: 'error',
                                title: "Deleting data fail!"
                            })
                        }
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    },
                    error: function(err) {
                        console.log(err);
                        Toast.fire({
                            icon: 'error',
                            title: err.responseJSON.message
                        })
                    }
                });
            });
            $('#edit-from').on('submit', function(e) {
                e.preventDefault();
                Toast.fire({
                    icon: 'info',
                    title: "Request in process...",
                })
                var formData = new FormData(this);
                $.ajax({
                    url: '{{ route('add-subject') }}',
                    processData: false,
                    contentType: false,
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        console.log(response);
                        if (response.status) {
                            Toast.fire({
                                icon: 'success',
                                title: "Updated Successfully!"
                            })
                        } else {
                            Toast.fire({
                                icon: 'error',
                                title: "Updating data fail!"
                            })
                        }
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    },
                    error: function(err) {
                        console.log(err);
                        Toast.fire({
                            icon: 'error',
                            title: err.responseJSON.message
                        })
                    }
                })
            });
        });
    </script>
@endsection
