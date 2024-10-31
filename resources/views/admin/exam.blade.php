@extends('layouts.admin_app')
@section('main')
<section class="content-header text-center">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-12">
          <h1>Schedule Exam</h1>
          <hr style="margin: 0 5% 0 5%;background-color: black;">
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>
<section class="content">
    <form class="upload-form" enctype="multipart/form-data" method="post">
        @csrf
        <div class="card">
            <div class="row">
                <div class="col-md-12">
                    <div class="card-header">
                        <h3 class="card-title">Drag & Drop <small><em>Assignment to upload</em></small></h3>
                    </div>
                    <div id="drop-zone" class="border p-4 text-center" style="margin: 2%; border: 2px dashed #cccccc;">
                        <p id="file-list">Drag & Drop file here or click to select a file</p>
                        <input type="file" id="file-input" style="display: none;">
                    </div>
                    <div  class="mt-3">
                        <!-- The uploaded file will be displayed here -->
                    </div>
                </div>
            </div>
            <!-- /.card -->
            <div class="row col-11 mt-3 ml-3">
                <div class="col-9 mb-3">
                    <label>Exam Title</label>
                    <input type="text" name="title" class="form-control" placeholder="Assignment Title" required />
                </div>
                
                <div class="col-3">
                    <label>Exam Type</label>
                    <select class="form-control select2 col-12" name="examtype">
                        <option>Internal</option>
                        <option>External</option>
                    </select>
                </div>
                <div class="col-3">
                    <select class="form-control select2 col-12 select-course" name="course_id" id="select-course">
                        <option selected disabled>Select Course</option>
                        @foreach ($streams as $cource)
                        <option value="{{$cource->id}}" data-sem="{{$cource->no_of_sem}}">{{$cource->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-4">
                    <select class="form-control select2 select2-sem col-12 select-sem" name="sem" id="select-sem">
                        <option selected disabled>Select Semester</option>
                    </select>
                </div>
                <div class="col-2">
                    <select class="form-control select2 col-12 select-sub" name="sub_id" id="select-sub">
                        <option selected disabled>Subject</option>
                    </select>
                </div>
                <div class="col-2">
                    <select class="form-control select2 col-12" name="div">
                        <option selected disabled>Division</option>
                        <option>A</option>
                        <option>B</option>
                        <option>C</option>
                        <option>D</option>
                        <option>E</option>
                    </select>
                </div>
            </div>
            <div class="row col-11 mt-3 ml-3">
                <div class="col-12">
                    <div class="form-group">
                        <label>Start & End Date and time range:</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="far fa-clock"></i></span>
                            </div>
                            <input type="text" class="form-control float-right reservationtime" name="date" id="reservationtime">
                        </div>
                        <!-- /.input group -->
                    </div>
                    <label>Decription of Assignment:</label>
                    <textarea id="summernote" class="summernote" name="dec"></textarea>
                </div>
            </div>
            <div class="col-11 mt-3 mb-2" style="margin: 0 5% 0 5%;">
                <input type="submit" class="btn btn-primary col-12" value="Upload & Schedule"></input>
            </div>
        </div>
    </form>
</section>

<!-- Content Header (Page header) -->
<section class="content-header text-center">
    <div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-12">
        <hr style="margin: 0 5% 0 5%;background-color: black;">
        <h1>Exam Progress</h1>
        <hr style="margin: 0 5% 0 5%;background-color: black;">
        </div>
    </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Projects</h3>

            <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                <i class="fas fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="card-body p-0">
        <table class="table table-striped projects">
            <thead>
                <tr>
                    <th >
                        #
                    </th>
                    <th >
                        Title
                    </th>
                    <th >
                        Subject
                    </th>
                    <th >
                        Sem(Div)
                    </th>
                    <th  class="text-center">
                        Time
                    </th>
                    <th  class="text-center">
                        Type
                    </th>
                    <th  class="text-center">
                        Status
                    </th>
                    <th >
                        Opration
                    </th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
    <!-- /.card-body -->
    </div>
    <!-- /.card -->

</section>

<div class="modal fade" id="editModal">
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
        <form class="update-form" enctype="multipart/form-data" method="post">
            @csrf
            <input type="hidden" name="id" value="">
            <div class="card">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card-header">
                            <h3 class="card-title">Drag & Drop <small><em>Assignment to upload</em></small></h3>
                        </div>
                        <input name="file" type="file" class="form-control col-11 ml-2" />
                        <small class="ml-2">don't upload if u don't want change file</small>
                    </div>
                </div>
                <!-- /.card -->
                <div class="row col-11 mt-3 ml-3">
                    <div class="col-8 mb-3">
                        <label>Assignment Title</label>
                        <input type="text" name="title" class="form-control" placeholder="Assignment Title" required />
                    </div>
                    <div class="col-4 mb-3">
                        <label>Type</label>
                        <select name="examtype" class="form-control"required>
                            <option value="Internal">Internal</option>
                            <option value="External">External</option>
                        <select>
                    </div>
                    <div class="col-4">
                        <select class="form-control select2 col-12 select-course" name="course_id" >
                            <option selected disabled>Select Course</option>
                            @foreach ($streams as $cource)
                                <option value="{{$cource->id}}" data-sem="{{$cource->no_of_sem}}">{{$cource->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-4">
                        <select class="form-control select2 select2-sem col-12 select-sem" name="sem">
                            <option selected disabled>Select Semester</option>
                        </select>
                    </div>
                    <div class="col-2">
                        <select class="form-control select2 col-12 select-sub" name="sub_id" >
                            <option selected disabled>Subject</option>
                        </select>
                    </div>
                    <div class="col-2">
                        <select class="form-control select2 col-12" name="div">
                            <option selected disabled>Division</option>
                            <option>A</option>
                            <option>B</option>
                            <option>C</option>
                            <option>D</option>
                            <option>E</option>
                        </select>
                    </div>
                </div>
                <div class="row col-11 mt-3 ml-3">
                    <div class="col-12">
                        <div class="form-group">
                            <label>Start & End Date and time range:</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="far fa-clock"></i></span>
                                </div>
                                <input type="text" class="form-control float-right reservationtime" name="date">
                            </div>
                            <!-- /.input group -->
                        </div>
                        <label>Decription of Assignment:</label>
                        <textarea class="summernote1" name="dec"></textarea>
                    </div>
                </div>
                <div class="col-11 mt-3 mb-2" style="margin: 0 5% 0 5%;">
                    <input type="submit" class="btn btn-primary col-12" value="Update"></input>
                </div>
            </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<div class="modal fade" id="modal-xl">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="overlay overlay-modal-xl">
              <i class="fas fa-2x fa-sync fa-spin"></i>
          </div>
        <div class="modal-header">
          <h4 class="modal-title">Exam Infomation</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div id="jsGrid"></div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
@endsection
@section('js')
<style>
    @media (max-width: 768px) {
        .jsgrid-header-cell, .jsgrid-cell {
            padding: 10px;
            font-size: 14px;
        }
    
        .jsgrid-control-field a {
            padding: 5px;
        }
    }
    #jsGrid {
    overflow-x: auto;
}
@media (max-width: 768px) {
    .desktop-only {
        display: none;
    }
}
</style>
<!-- date-range-picker -->
<script src="{{url('/plugins/daterangepicker/daterangepicker.js')}}"></script>
<!-- dropzonejs -->
<script src="{{url('/plugins/dropzone/min/dropzone.min.js')}}"></script>
<!-- Summernote -->
<script src="{{url('/plugins/summernote/summernote-bs4.min.js')}}"></script>
<style src="{{url('/plugins/jsgrid/jsgrid-theme.min.css')}}"></style>
<style src="{{url('/plugins/jsgrid/jsgrid.min.css')}}"></style>
<script src="{{url('/plugins/jsgrid/demos/db.js')}}"></script>
<script src="{{url('/plugins/jsgrid/jsgrid.min.js')}}"></script>
<script>
    $(document).ready(function() {
        $('.upload-form')[0].reset();
        // Summernote
        $('.summernote').summernote()
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
        $('.select2').select2({theme: 'bootstrap4'});

        $('.projects').DataTable({
            serverSide: true,
            ordering: false,
            lengthChange: false,
            autoWidth: true,
            responsive: true,
            ajax: {
                url: '{{ route("getExamStudents") }}',
                dataSrc: 'data',
            },
            columns: [
                { 
                    data: null, 
                    title: '#', 
                    className: 'text-center',
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1; // Index column
                    }
                },
                { data: 'title', title: 'Title' }, // Title column
                { data: 'subject.name', title: 'Subject' }, // Subject column
                {
                    data: null,
                    title: 'Sem(Div)',
                    render: function(data) {
                        // Combine `sem` from `subject` with `div`
                        return `${data.subject.sem} (${data.div || ''})`;
                    }
                },
                {
                    data: null,
                    title: 'Time',
                    className: 'text-center',
                    render: function(data) {
                        return `${data.StartTime} - ${data.EndTime}`;
                    }
                },
                {
                    data: null,
                    title: 'Status',
                    className: 'text-center',
                    render: function(data) {
                        const now = new Date();
                        const startTime = new Date(data.StartTime);
                        const endTime = new Date(data.EndTime);

                        if (startTime > now) {
                            return '<span class="badge badge-warning">Upcoming</span>';
                        } else if (startTime <= now && endTime > now) {
                            return '<span class="badge badge-info">Ongoing</span>';
                        } else {
                            return '<span class="badge badge-success">Done</sapn>';
                        }
                    }
                },
                {
                    data: null,
                    title: 'examtype',
                    className: 'text-center',
                    render: function(data) {
                        return `${data.examtype	}`;
                    }
                },
                {
                    data: null,
                    title: 'Operation',
                    render: function(data) {
                        return `
                            <button class="btn btn-sm btn-primary edit-data" data-id="${data.id}">
                                <i class="fas fa-pencil-alt"></i> Edit
                            </button>
                            <button class="btn btn-sm btn-danger delete-data" data-id="${data.id}">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                            <button class="btn btn-sm btn-primary view-data" data-id="${data.id}">
                                <i class="fas fa-folder"></i> View
                            </button>`;
                    }
                }
            ]
        });

        // $(document).on('click', '.view-data', function() {
        //     $('.overlay-modal-xl').show();
        //     $('#modal-xl').modal('show')
        //     var id = $(this).data('id');
        //     if ($.fn.DataTable.isDataTable('#jsGrid')) {
        //         $('jsGrid').DataTable().destroy();
        //     }
        //     $('jsGrid').DataTable({
        //         serverSide: true,
        //         ordering: false,
        //         "lengthChange": false,
        //         "autoWidth": true,
        //         "responsive": true,
        //         ajax: {
        //             url: '{{ route("getProjectSubmissions") }}',
        //             data: { id: id },
        //             dataSrc: 'data'
        //         },
        //         columns: [
        //             { data: 'en_no' },
        //             { data: 'name' },
        //             { data: 'submitted' },
        //             { data: 'created_at' },
        //             { data: null, render: function(data, type, row) { 
        //                 return `
        //                     <!--<button class="btn btn-sm btn-primary edit-data" data-id="${data.id}"><i class="fas fa-pencil-alt"></i> Edit</button>
        //                     <br>
        //                     <button class="btn btn-sm btn-danger delete-data" data-id="${data.id}"><i class="fas fa-trash"></i> Delete</button>
        //                     <button class="btn btn-sm btn-primary view-data" data-id="${data.id}"><i class="fas fa-folder"></i> View</button>-->
        //                 `;
        //             } } // Action column
        //         ]
        //     });
        //     $('.overlay-modal-xl').hide();
        // });
        $(document).on('click', '.view-data', function() {
            $('.overlay-modal-xl').show();
            $('#modal-xl').modal('show');

            var id = $(this).data('id');

            $("#jsGrid").jsGrid({
                width: "100%",
                height: "400px",
                filtering: false,
                inserting: false,
                editing: true,
                sorting: true,
                paging: true,
                autoload: true,
                pageSize: 10,
                pageButtonCount: 5,

                controller: {
                    loadData: function(filter) {
                        return $.ajax({
                            type: "GET",
                            url: '{{ route("getExamStudents") }}',
                            data: { id: id },
                            dataType: "json"
                        }).then(function(response) {
                            $('.overlay-modal-xl').hide();
                            return response.data;
                        });
                    }
                },

                fields: [
                    { name: "en_no", title: "Enrollment No", type: "text", width: 50, editing: false },
                    { name: "name", title: "Name", type: "text", width: 100, editing: false },
                    { name: "marks", title: "Submitted", type: "text", width: 50, editing: true },
                    // {
                    //     title: "Actions",
                    //     itemTemplate: function(_, item) {
                    //         return `<button class="btn btn-sm btn-danger delete-data" data-id="${item.id}">
                    //                     <i class="fas fa-trash"></i> Delete
                    //                 </button>`;
                    //     },
                    //     width: 120
                    // }
                ]
            });
        });

        $(document).on('change', '.select-course', function() {
            const selectedSem = $(this).find(':selected').attr('data-sem');
            const semesterDropdown = $(this).closest('.row').find('.select-sem');

            // Clear and populate semester dropdown
            semesterDropdown.empty().append('<option selected disabled>Select Semester</option>');
            if (selectedSem) {
                for (let i = 1; i <= selectedSem; i++) {
                    semesterDropdown.append(`<option value="${i}">Sem${i}</option>`);
                }
            }
        });
        $(document).on('change', '.select-sem', function() {
            const sem = $(this).val();
            const courseDropdown = $(this).closest('.row').find('.select-course');
            const subjectDropdown = $(this).closest('.row').find('.select-sub');

            // AJAX request to get subjects based on selected semester and course
            $.ajax({
                url: '{{ route("getSubject") }}',
                type: 'GET',
                data: {
                    sem: sem,
                    course_id: courseDropdown.val(),
                },
                success: function(response) {
                    subjectDropdown.empty().append('<option selected disabled>Subject</option>');
                    if (response.length > 0) {
                        response.forEach((element) => {
                            subjectDropdown.append(`<option value="${element.id}">${element.name}</option>`);
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
        const dropZone = document.getElementById('drop-zone');
        const fileInput = document.getElementById('file-input');
        const fileList = document.getElementById('file-list');
        let selectedFile = null;

        // Open file input dialog when the drop zone is clicked
        dropZone.addEventListener('click', () => {
            clearFile(); // Clear the file name or text when clicking the drop zone
            fileInput.click();
        });
        // Handle file input change
        fileInput.addEventListener('change', (event) => {
            handleFile(event.target.files[0]);
        });
        // Prevent default behavior (Prevent file from being opened)
        dropZone.addEventListener('dragover', (event) => {
            event.preventDefault();
            dropZone.classList.add('bg-light');
        });
        dropZone.addEventListener('dragleave', () => {
            dropZone.classList.remove('bg-light');
        });
        // Handle file drop
        dropZone.addEventListener('drop', (event) => {
            event.preventDefault();
            dropZone.classList.remove('bg-light');
            handleFile(event.dataTransfer.files[0]);
        });
        // Handle the selected file
        function handleFile(file) {
            if (file) {
                selectedFile = file;
                fileList.innerHTML = `
                    <div class="file-item ml-4">
                        ${file.name}
                    </div>
                `;
            }
        }
        // Clear the selected file and reset the drop zone
        function clearFile() {
            selectedFile = null;
            fileInput.value = ''; // Clear the file input
            fileList.innerHTML = '<p class="text-muted">Drag & Drop file here or click to select a file</p>';
        }
        $('.upload-form').on('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            formData.append('file', selectedFile);
            $.ajax({
                url: '{{ route("examCreateOrUpdate") }}',
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
        $('.projects').on('click', '.delete-data', function(){
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    var id = $(this).data('id');
                    $.ajax({
                        url: '{{ route("deleteExam") }}',
                        method: 'DELETE',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            id: id
                        },
                        success: function(response) {
                            console.log(response);
                            if(response.status){
                                Toast.fire({
                                    icon:'success',
                                    title: "Deleted Successfully!"
                                })
                            } else{
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
                }
            })
        })
        $(document).on('click', '.edit-data', function() {
            var id = $(this).data('id');
            $('.overlay').show(); // Show overlay
            $('#editModal').modal('show');

            // AJAX request to get the assignment data
            $.ajax({
                url: '{{ route("getExamStudents") }}',
                data: { id: id },
                method: 'GET',
                success: function(response) {
                    // Reset form and populate with fetched data
                    $('.update-form').trigger('reset');
                    console.log(response);
                    $('.update-form input[name="id"]').val(response.id);
                    $('.update-form').attr('action', '{{ url("updateAssignment") }}/' + id);
                    $('.update-form input[name="title"]').val(response.title);
                    $(".summernote1").summernote("code", response.dec);
                    $('.update-form input[name="date"]').val(formatDate(response.StartTime)+" - "+formatDate(response.EndTime));

                    // Update course, semester, and subject dropdowns
                    $('.update-form select[name="course_id"]').val(response.course_id).trigger('change');
                    $('.update-form select[name="div"]').val(response.div).trigger('change');
                    $('.update-form select[name="sem"]').val(response.subject.sem).trigger('change');
                    $('.update-form select[name="examtype"]').val(response.examtype).trigger('change');
                    setTimeout(function() {
                        $('.update-form select[name="sub_id"]').val(response.sub_id).trigger('change');
                    }, 1000);
                    // $('.update-form select[name="course_id"]').val(response.course_id).trigger('change');

                    // Hide overlay after data is populated
                    $('.overlay').hide();
                },
                error: function(err) {
                    console.log(err);
                    $('.overlay').hide();
                    Toast.fire({
                        icon: 'error',
                        title: err.responseJSON.message || "Error fetching data"
                    });
                }
            });
        });
        $('.update-form').on('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            $.ajax({
                url: '{{ route("examCreateOrUpdate") }}',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    console.log(response);
                    if(response.status){
                        Toast.fire({
                            icon: 'success',
                            title: "Updated Successfully!"
                        })
                    } else{
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
            });
        });
        $("#search_course_id").on("change", function() {
            var selectedData = $(this).select2("data")[0];
            var sem = selectedData ? selectedData.element.dataset.sem : null;
            for(var i=1;i<=sem;i++){
                $("#search_sem").append('<option value="'+i+'">'+i+'</option>');
            }
        });
        $("#searchLeb").on("submit", function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                url: '{{ route("searchlab") }}',
                method: 'GET',
                data: formData,
                success: function(res) {
                    $('.upload-form').find("[name=div]").val(res.div).trigger('change')
                    $('.upload-form').find("[name=course_id]").val(res.course_id).trigger('change')
                    $('.upload-form').find("[name=sem]").val(res.sem).trigger('change')
                    setTimeout(function() {
                        $('.upload-form').find("[name=sub_id]").val(res.subject_id).trigger('change')
                    }, 1000)
                    $('.upload-form').find("[name=date]").val(res.StartDate+' - '+res.EndDate)
                    
                },
                error: function(err) {
                    console.log(err);
                    Toast.fire({
                        icon: 'error',
                        title: err.responseJSON.message
                    })
                }
            });
        })
    });

</script>
@endsection