@extends('layouts.admin_app')
@section('main')
    <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div class="row mt-2">
                <div class="col-12">
                    <div class="card card-default" style="padding: 20px;">
                        <center>
                            <h2>Time Table Configuration</h1>
                        </center>
                        <form method="POST" id="add-timetable">
                            @csrf
                            <label for="day">Day</label>
                            <select id="day" class="form-control select2" name="day"
                                data-placeholder="Select a Course">
                                <option selected disabled>Select Day</option>
                                <option value="Monday">Monday</option>
                                <option value="Tuesday">Tuesday</option>
                                <option value="Wednesday">Wednesday</option>
                                <option value="Thursday">Thursday</option>
                                <option value="Friday">Friday</option>
                                <option value="Sunday">Sunday</option>
                            </select>
                            <label for="div">Select Division</label>
                            <select id="div" class="form-control" name="div">
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                                <option value="D">D</option>
                                <option value="E">E</option>
                            </select>
                            <label for="Subject">Select Course</label>
                            <select id="course" class="form-control select2" name="course_id">
                                <option selected disabled>select Course</option>
                                @if ($courses)
                                    @foreach ($courses as $course)
                                        <option data-sem="{{ $course->no_of_sem }}" value="{{ $course->id }}">
                                            {{ $course->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <label for="sem">Select SEM</label>
                            <select id="sem" class="form-control" name="sem">
                                <option selected disabled>select Sem</option>
                            </select>
                            <label for="Subject">Subject</label>
                            <select type="text" id="Subject" class="form-control" name="subject_id"
                                placeholder="Subject">
                                <option selected disabled>select Subject</option>
                            </select>
                            <div class="form-group">
                                <label>Start & End Date and time range:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="far fa-clock"></i></span>
                                    </div>
                                    <input type="text" class="form-control float-right reservationtime" name="date"
                                        id="reservationtime">
                                </div>
                                <!-- /.input group -->
                            </div>
                            <button class="btn btn-success mt-2" type="submit"><i class="fas fa-book"></i> Add
                                configuration</button>
                        </form>
                    </div>
                </div>
                <div class="container mt-5">
                    <table class="table table-bordered mb-5" id="dataTable">
                        <thead>
                            <tr class="thead-dark">
                                <th scope="col">Day</th>
                                <th scope="col">Sub. Name</th>
                                <th scope="col">Course Name</th>
                                <th scope="col">Sem</th>
                                <th scope="col">Div</th>
                                <th scope="col">Time</th>
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
        </div>
    </section>
    <div class="modal fade" id="edit-subject-form" tabindex="-1" aria-labelledby="editSubjectLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editForm">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="editSubjectLabel">Edit Subject</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" value="">
                        <div class="mb-3">
                            <label for="course" class="form-label">course</label>
                            <select id="mcourse" class="form-control" name="course_id" required>
                                <option selected disabled>Select Course</option>
                                @if ($courses)
                                    @foreach ($courses as $course)
                                        <option value="{{ $course->id }}">{{ $course->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="from-group row mb-3 col-12">
                            <div class="col-5 mr-1">
                                <label for="name" class="form-label">Day</label>
                                <select class="form-control col-12" name="day" required>
                                    <option selected disabled>Select Day</option>
                                    <option value="Monday">Monday</option>
                                    <option value="Tuesday">Tuesday</option>
                                    <option value="Wednesday">Wednesday</option>
                                    <option value="Thursday">Thursday</option>
                                    <option value="Friday">Friday</option>
                                    <option value="Sunday">Sunday</option>
                                </select>
                            </div>
                            <div class="ml-1 col-3">
                                <label for="name" class="form-label">Div</label>
                                <select id="mdiv" class="form-control col-12" name="div">
                                    <option value="A">A</option>
                                    <option value="B">B</option>
                                    <option value="C">C</option>
                                    <option value="D">D</option>
                                    <option value="E">E</option>
                                </select>
                            </div>
                            <div class="ml-1 col-3">
                                <label for="sem" class="form-label">Sem</label>
                                <select id="msem" class="form-control col-12" name="sem">
                                    @for ($i = 1; $i < 9; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="subject_code" class="form-label">Subject</label>
                            <select type="text" class="form-control" name="subject_id" required>
                                <option selected disabled>Select Subject</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Start &amp; End Date and time range:</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="far fa-clock"></i></span>
                                </div>
                                <input type="text" class="form-control float-right reservationtime" name="date"
                                    id="reservationtime">
                            </div>
                            <!-- /.input group -->
                        </div>
                        <!-- Add other fields as necessary -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
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
        $(document).ready(function() {
            var sub_id = null;
            Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            })
            $('.reservationtime').daterangepicker({
                timePicker: true,
                timePickerIncrement: 10,
                locale: {
                    format: 'MM/DD/YYYY hh:mm A'
                }
            })
            $('.select2').select2({
                theme: 'bootstrap4'
            });
            $('#dataTable').DataTable({
                serverSide: true,
                ordering: false,
                lengthChange: false,
                autoWidth: true,
                responsive: true,
                select: {
                    style: "multi",
                },
                dom: "Bfrtip",
                buttons: [{
                        text: 'Delete Selected Row',
                        action: function(e, dt, node, config) {
                            var selectedRow = dt.row({
                                selected: true
                            });
                            if (selectedRow.any()) {
                                var rowData = selectedRow.data();
                                $.ajax({
                                    url: '{{ route('deleteTimeTable') }}',
                                    method: 'DELETE',
                                    data: {
                                        "_token": "{{ csrf_token() }}",
                                        "id[]": rowData.id
                                    },
                                    success: function(response) {
                                        if (response.status) {
                                            selectedRow.remove().draw(false);
                                            Toast.fire({
                                                icon: 'success',
                                                title: "Deleted Successfully!"
                                            });
                                        } else {
                                            Toast.fire({
                                                icon: 'error',
                                                title: "Deleting data failed!"
                                            });
                                        }
                                    },
                                    error: function(err) {
                                        console.log(err);
                                        Toast.fire({
                                            icon: 'error',
                                            title: err.responseJSON.message
                                        });
                                    }
                                });
                            }
                        }
                    },
                    {
                        text: 'Edit Selected Row',
                        action: function(e, dt, node, config) {
                            var rows = dt.rows({
                                selected: true
                            }).data();
                            if (rows.length !== 1) {
                                Toast.fire({
                                    icon: 'warning',
                                    title: "Please select exactly one row to edit!"
                                });
                                return;
                            }

                            var rowData = rows[0];

                            $('.overlay').show();
                            $('#loadingModal').modal('show');

                            $.ajax({
                                url: "{{ route('getSubject') }}", // Adjust to your edit route
                                method: 'GET',
                                data: {
                                    id: rowData.id
                                },
                                success: function(res) {
                                    $('.overlay').hide();
                                    $('#editForm').find('[name="id"]').val(res.id);
                                    $('#editForm').find('[name="name"]').val(res.name);
                                    $('#editForm').find('[name="course_id"]').val(res.course_id);
                                    $('#editForm').find('[name="sem"]').val(res.sem).trigger('change');
                                    $('#editForm').find('[name="date"]').val(rowData.time);
                                    $('#editForm').find('[name="day"]').val(rowData.day);
                                    setTimeout(function() {
                                        $('#editForm').find('[name="subject_id"]').val(rowData.id);
                                    },1000)
                                    $('#edit-subject-form').modal('show');
                                },
                                error: function(err) {
                                    console.log(err);
                                    $('.overlay').hide();
                                    Toast.fire({
                                        icon: 'error',
                                        title: err.responseJSON.message
                                    });
                                }
                            });
                        }
                    }
                ],
                ajax: {
                    url: "{{ route('getTimetable') }}",
                    dataSrc: 'data'
                },
                columns: [{
                        data: 'day'
                    },
                    { data: 'subject_name' },
                    { data: 'course_name' },
                    { data: 'sem' },
                    { data: 'div'},
                    { data: 'time' },
                    { data: null,
                        render: function(data, type, row) {
                            // return '<button class="btn btn-sm btn-primary edit-data" data-id="' + data.id + '">edit</button><br><button class="btn btn-sm btn-danger delete-data" data-id="' + data.id + '">Delete</button>';
                            return 'Buttons are top of table';
                        }
                    }
                ]
            });
            $("#course").on("change", function() {
                var sem = $(this).select2("data")[0].element.dataset.sem ?? 0;
                for (var i = 1; i <= sem; i++) {
                    $("#sem").append('<option value="' + i + '">' + i + '</option>');
                }
            });
            $("#sem").on('change', function() {
                const sem = $(this).val();
                const courseDropdown = $("#course");
                const subjectDropdown = $("#Subject");

                $.ajax({
                    url: '{{ route('getSubject') }}',
                    type: 'GET',
                    data: {
                        sem: sem,
                        course_id: courseDropdown.val(),
                    },
                    success: function(response) {
                        subjectDropdown.empty().append(
                            '<option selected disabled>Subject</option>');
                        if (response.length > 0) {
                            response.forEach((element) => {
                                subjectDropdown.append(
                                    `<option value="${element.id}">${element.name}</option>`
                                );
                            });
                        } else {
                            Toast.fire({
                                icon: 'info',
                                title: "No Subject Found!"
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                        Toast.fire({
                            icon: 'error',
                            title: "Error fetching subjects!"
                        });
                    }
                });
            });
            $("#add-timetable").on("submit", function(e) {
                e.preventDefault();
                Toast.fire({
                    icon: 'info',
                    title: "Request in process...",
                });
                var formData = new FormData(this);
                $.ajax({
                    url: "{{ route('add-timetable') }}",
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
                            title: "Adding data fail!"
                        })
                    }
                })
            })
            $("#msem").on('change', function() {
                getTimeTable();
            })
            function getTimeTable() {
                const sem = $("#msem").val();
                const courseDropdown = $("#mcourse");
                const subjectDropdown = $('#editForm').find('[name="subject_id"]')
                $.ajax({
                    url: '{{ route('getSubject') }}',
                    type: 'GET',
                    data: {
                        sem: sem,
                        course_id: courseDropdown.val(),
                    },
                    success: function(response) {
                        subjectDropdown.empty().append(
                            '<option selected disabled>Subject</option>');
                        if (response.length > 0) {
                            response.forEach((element) => {
                                subjectDropdown.append(
                                    `<option value="${element.id}" >${element.name}</option>`
                                );
                            });
                        } else {
                            Toast.fire({
                                icon: 'info',
                                title: "No Subject Found!"
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                        Toast.fire({
                            icon: 'error',
                            title: "Error fetching subjects!"
                        });
                    }
                })
            }
            $("#editForm").on("submit", function(e) {
                e.preventDefault();
                Toast.fire({
                    icon: 'info',
                    title: "Request in process...",
                });
                var formData = new FormData(this);
                $.ajax({
                    url: "{{ route('add-timetable') }}",
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
                            title: "Updating data fail!"
                        })
                    }
                })
            })
        })
    </script>
@endsection
