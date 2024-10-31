@extends('layouts.admin_app')
@section('main')
<section class="content-header text-center">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-12">
          <h1>Upload Assignemnt</h1>
          <hr style="margin: 0 5% 0 5%;background-color: black;">
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>
<section class="content">
    <form id="searchLeb">
        <div class="row col-12 mb-3">
            @csrf
            <select class="form-control select2 col-3" name="sem">
                <option selected disabled>Select Day</option>
                <option value="monday">Monday</option>
                <option value="Tuesday">Tuesday</option>
                <option value="Wensday">Wensday</option>
                <option value="Thursday">Thursday</option>
                <option value="Friday">Friday</option>
                <option value="Saturday">Saturday</option>
            </select>
            <select class="form-control select2 col-3 ml-2" id="search_cource_id" name="cource_id">
                <option selected disabled>Select Course</option>
                @foreach ($cources as $cource)
                    <option value="{{$cource->id}}" data-sem="{{$cource->no_of_sem}}">{{$cource->name}}</option>
                @endforeach
            </select>
            <select class="form-control select2 col-1" id="search_sem" name="sem">
                <option selected disabled>Select sem</option>
            </select>
            <select class="form-control select2 col-2" name="div">
                <option selected disabled>Select div</option>
                <option value="A">A</option>
                <option value="B">B</option>
                <option value="C">C</option>
                <option value="D">D</option>
                <option value="E">E</option>
            </select>
            <input type="submit" class="btn btn-info" value="Search">
        </div>
    </form>
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
                <div class="col-12 mb-3">
                    <label>Assignment Title</label>
                    <input type="text" name="title" class="form-control" placeholder="Assignment Title" required />
                </div>
                <div class="col-4">
                    <select class="form-control select2 col-12 select-course" name="cource_id" id="select-course">
                        <option selected disabled>Select Course</option>
                        @foreach ($cources as $cource)
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
{{-- <section class="content-header text-center">
    <div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-12">
        <hr style="margin: 0 5% 0 5%;background-color: black;">
        <h1>Assignemnt List</h1>
        <hr style="margin: 0 5% 0 5%;background-color: black;">
        </div>
    </div>
    </div><!-- /.container-fluid -->
</section>
<section class="content" style="margin: 0 5% 0 5%;">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Assignment List</h3>
            <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                <i class="fas fa-minus"></i>
            </button>
            </div>
        </div>
        <div class="card-body p-0">
            <table>
                <table class="table table-bordered mb-5" id="dataTable">
                    <thead>
                        <tr class="thead-dark">
                            <th scope="col">ID</th>
                            <th scope="col">Title</th>
                            <th scope="col">Subject</th>
                            <th scope="col">Sem</th>
                            <th scope="col">Div</th>
                            <th scope="col">Time</th>
                            <th scope="col"><i class="fas fa-cogs"></i></th>
                        </tr>
                    </thead>
     
                    <tbody>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </table>
        </div>
</section> --}}

<!-- Content Header (Page header) -->
<section class="content-header text-center">
    <div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-12">
        <hr style="margin: 0 5% 0 5%;background-color: black;">
        <h1>Assignemnt Progress</h1>
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
                    <th>
                        Project Progress
                    </th>
                    <th  class="text-center">
                        Time
                    </th>
                    <th  class="text-center">
                        Status
                    </th>
                    <th >
                    </th>
                </tr>
            </thead>
            <tbody>
                {{-- <tr>
                    <td>
                        #
                    </td>
                    <td>
                        <a>
                            AdminLTE v3
                        </a>
                        <br/>
                        <small>
                            Created 01.01.2019
                        </small>
                    </td>
                    <td>
                        <ul class="list-inline">
                            <li class="list-inline-item">
                                <img alt="Avatar" class="table-avatar" src="../../dist/img/avatar.png">
                            </li>
                            <li class="list-inline-item">
                                <img alt="Avatar" class="table-avatar" src="../../dist/img/avatar2.png">
                            </li>
                            <li class="list-inline-item">
                                <img alt="Avatar" class="table-avatar" src="../../dist/img/avatar3.png">
                            </li>
                            <li class="list-inline-item">
                                <img alt="Avatar" class="table-avatar" src="../../dist/img/avatar4.png">
                            </li>
                        </ul>
                    </td>
                    <td class="project_progress">
                        <div class="progress progress-sm">
                            <div class="progress-bar bg-green" role="progressbar" aria-valuenow="57" aria-valuemin="0" aria-valuemax="100" style="width: 57%">
                            </div>
                        </div>
                        <small>
                            57% Complete
                        </small>
                    </td>
                    <td class="project-state">
                        <span class="badge badge-success">Success</span>
                    </td>
                    <td class="project-actions text-right">
                        <a class="btn btn-primary btn-sm" href="#">
                            <i class="fas fa-folder">
                            </i>
                            View
                        </a>
                        <a class="btn btn-info btn-sm" href="#">
                            <i class="fas fa-pencil-alt">
                            </i>
                            Edit
                        </a>
                        <a class="btn btn-danger btn-sm" href="#">
                            <i class="fas fa-trash">
                            </i>
                            Delete
                        </a>
                    </td>
                </tr> --}}
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
                    <div class="col-12 mb-3">
                        <label>Assignment Title</label>
                        <input type="text" name="title" class="form-control" placeholder="Assignment Title" required />
                    </div>
                    <div class="col-4">
                        <select class="form-control select2 col-12 select-course" name="cource_id" >
                            <option selected disabled>Select Course</option>
                            @foreach ($cources as $cource)
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
          <h4 class="modal-title">Assingment Infomation</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <table class="table table-striped student-projects">
            <thead>
                <tr>
                    <th>En.NO.</th>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Uploaded At</th>
                    <th><i class="fas fa-cogs"></i></th>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </thead>
          </table>
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
<!-- date-range-picker -->
<script src="{{url('/plugins/daterangepicker/daterangepicker.js')}}"></script>
<!-- dropzonejs -->
<script src="{{url('/plugins/dropzone/min/dropzone.min.js')}}"></script>
<!-- Summernote -->
<script src="{{url('/plugins/summernote/summernote-bs4.min.js')}}"></script>
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

        // $('#dataTable').DataTable({
        $('.projects').DataTable({
            serverSide: true,
            ordering: false,
            "lengthChange": false,
            "autoWidth": true,
            "responsive": true,
            ajax: {
            url : '{{route("getAssignmentList")}}',
            dataSrc: 'data'
            },
            columns: [
                { data: null, render: function(data, type, row, meta) { return meta.row + meta.settings._iDisplayStart + 1; } }, // Index column
                { data: 'title' },
                { data: 'subject' },
                { data: 'sem' },
                { data: 'submission_percentage' },
                { data: 'time' },
                { data: 'status' },
                { data: null, render: function(data, type, row) { return '<button class="btn btn-sm btn-primary edit-data" data-id="'+data.id+'"><i class="fas fa-pencil-alt"></i> edit</button><br><button class="btn btn-sm btn-danger delete-data" data-id="'+data.id+'"><i class="fas fa-trash"></i> Delete</button><button class="btn btn-sm btn-primary view-data" data-id="'+data.id+'"><i class="fas fa-folder"></i> View</button>'; } } // Action column
            ]
        });
        $(document).on('click', '.view-data', function() {
            $('.overlay-modal-xl').show();
            $('#modal-xl').modal('show')
            var id = $(this).data('id');
            if ($.fn.DataTable.isDataTable('.student-projects')) {
                $('.student-projects').DataTable().destroy();
            }
            $('.student-projects').DataTable({
                serverSide: true,
                ordering: false,
                "lengthChange": false,
                "autoWidth": true,
                "responsive": true,
                ajax: {
                    url: '{{ route("getProjectSubmissions") }}',
                    data: { id: id },
                    dataSrc: 'data'
                },
                columns: [
                    { data: 'en_no' },
                    { data: 'name' },
                    { data: 'submitted' },
                    { data: 'created_at' },
                    { data: null, render: function(data, type, row) { 
                        return `
                            <!--<button class="btn btn-sm btn-primary edit-data" data-id="${data.id}"><i class="fas fa-pencil-alt"></i> Edit</button>
                            <br>
                            <button class="btn btn-sm btn-danger delete-data" data-id="${data.id}"><i class="fas fa-trash"></i> Delete</button>
                            <button class="btn btn-sm btn-primary view-data" data-id="${data.id}"><i class="fas fa-folder"></i> View</button>-->
                        `;
                    } } // Action column
                ]
            });
            $('.overlay-modal-xl').hide();
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
                    cource_id: courseDropdown.val(),
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
                url: '{{ route("upload-assignment") }}',
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
                    } else{
                        Toast.fire({
                            icon: 'error',
                            title: "Uploading data fail!"
                        })
                    }
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
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
                        url: '{{ route("deleteAssignment") }}',
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
                url: '{{ route("editAssignment", '') }}/' + id,
                method: 'GET',
                success: function(response) {
                    // Reset form and populate with fetched data
                    $('.update-form').trigger('reset');
                    console.log(response);
                    $('.update-form input[name="id"]').val(response.id);
                    $('.update-form').attr('action', '{{ url("updateAssignment") }}/' + id);
                    $('.update-form input[name="title"]').val(response.title);
                    $(".summernote1").summernote("code", response.dec); // Populate summernote
                    $('.update-form input[name="date"]').val(response.date);

                    // Update course, semester, and subject dropdowns
                    $('.update-form select[name="cource_id"]').val(response.cource_id).trigger('change');
                    $('.update-form select[name="sem"]').val(response.sem).trigger('change');
                    $('.update-form select[name="div"]').val(response.div).trigger('change');
                    setTimeout(function() {
                        $('.update-form select[name="sub_id"]').val(response.sub_id).trigger('change');
                    }, 1000);
                    // $('.update-form select[name="cource_id"]').val(response.cource_id).trigger('change');

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
                url: '{{ route("update-assignment") }}',
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
        $("#search_cource_id").on("change", function() {
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
                    $('.upload-form').find("[name=cource_id]").val(res.course_id).trigger('change')
                    $('.upload-form').find("[name=sem]").val(res.sem).trigger('change')
                    setTimeout(function() {
                        $('.upload-form').find("[name=sub_id]").val(res.subject_id).trigger('change')
                    }, 1000)
                    $('.upload-form').find("[name=date]").val(formatDate(res.StartDate)+' - '+formatDate(res.EndDate))
                    
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