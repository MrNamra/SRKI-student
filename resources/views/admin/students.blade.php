@extends('layouts.admin_app')
@section('main')
<div class="row justify-content-center ">
    <form action="" method="post" class="col-md-8" enctype="multipart/form-data">
        @csrf
        <h4 class="col-6">Add Bulk Students</h4>
        <p>Note:- This Procces take time depend on Student Data <b>Max execution time 1 hour 40 munites</b></p>
        <small><a href="{{ url('/csv/Example.csv') }}">Here Example of csv file </a></small>
        <div class="col-md-12">
            <div class="card card-default" style="padding: 20px;">
                <label for="my-select">Upload CSV</label>
                <input type="file" id="file" name="file" class="form-control" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" />
                <button class="btn btn-warning mt-2">Submit</button>
            </div>
            @error('file')
                <p>{{ $message }}</p>
            @enderror
            @error('sem')
                <p>{{ $message }}</p>
            @enderror
            @error('course_id')
                <p>{{ $message }}</p>
            @enderror
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
        </div>
    </form>
</div>
<hr style="margin: 0 5% 0 5%; background-color: #000;">
<div class="row justify-content-center">
    <h3><b>List of Students</b></h3>
</div>
<hr style="margin: 0 5% 5% 5%; background-color: #000;">
<div class="row justify-content-center ">
    <div class="container ">
        <table class="table table-bordered mb-5" id="dataTable">
            <thead>
                <tr class="thead-dark">
                    <th scope="col">En NO.</th>
                    <th scope="col">name</th>
                    <th scope="col">Course Name</th>
                    <th scope="col">Sem</th>
                    <th scope="col">Div</th>
                    <th scope="col">IP</th>
                    <th scope="col"><i class="fas fa-cogs"></i></th>
                </tr>
            </thead>

            <tbody>
                {{-- @foreach($subjects as $subject) --}}
                <tr>
                    <th></th>
                    <td></td>
                    <td></td>
                    <td></td>
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
<hr style="margin: 0 5% 0 5%; background-color: #000;">
<div class="row justify-content-center">
    <h3><b>Studens promotion/Demotion</b></h3>
</div>
<hr style="margin: 0 5% 5% 5%; background-color: #000;">
Note:- press any button below this <b>at your own risk</b> beacause after press button data start update and can't be undo

<div class="row mt-5">
    <!-- /.col -->
    <div class="col-6">
      <div class="info-box bg-success promote-demote" data-data="promote">
        <span class="info-box-icon"><i class="fas fa-chevron-circle-up"></i></span>

        <div class="info-box-content">
          <span class="info-box-text info-box-number">Yes I Ready to take Risk</span>
          <small>
            - All Students are Promoted to +1 sem<br>
            - last sem students Data will be removed(unrecoverable)<br>(file, data from database, etc.)
          </small>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-6">
      <div class="info-box bg-danger promote-demote" data-data="demote">
        <span class="info-box-icon"><i class="fas fa-chevron-circle-down"></i></span>

        <div class="info-box-content">
            <span class="info-box-text info-box-number">Yes I Ready to take Risk</span>
          <small>
            - All Students are Demoted to -1 sem<br>
            - last sem students Data will be not Recover any more<br>(file, data from database, etc.)
          </small>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
    <!-- /.col -->
  </div>

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
        <br> <small id="enno">Wait...</small>
        <form method="post" id="edit-from">
          <div class="modal-body">
            @csrf
            <input type="hidden" name="enrollment_no" placeholder="enrollment_no" />
            <label class="control-label">Sudent Name</label>
            <input type="text" name="name" class="form-control" placeholder="Name" />
            <label>IP:</label>
            <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-laptop"></i></span>
            </div>
            <input type="text" class="form-control" name="ip" data-inputmask="'alias': 'ip'" data-mask>
            </div>
            <label class="control-label">Course</label>
            <select class="form-control select2" name="course_id" data-placeholder="Select a Corce">
              @if ($streams)
                @foreach ($streams as $stream)
                <option value="{{$stream->id}}">{{$stream->name}}</option>
                @endforeach
              @endif
            </select>
            <label class="control-label">Sem</label>
            <select class="form-control" name="sem">
              @for ($i = 1; $i <= 8; $i++)
                <option value="{{$i}}">{{$i}}</option>
              @endfor
            </select>
            <label class="control-label">Div</label>
            <input type="text" name="div" class="form-control" placeholder="Subject Code" />
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
        $(document).ready(function() {
            var Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
            //Money Euro
            $('[data-mask]').inputmask()
            
            $('#dataTable').DataTable({
                serverSide: true,
                lengthChange: false,
                autoWidth: true,
                ordering: false,
                responsive: true,
                ajax: {
                    url: '{{ route("get-students-list") }}',
                    dataSrc: 'data',
                },
                columns: [
                    // { data: null, render: function(data, type, row, meta) { return meta.row + meta.settings._iDisplayStart + 1; } }, // Index column
                    { data: 'enrollment_no' },
                    { data: 'name' },
                    { data: 'course_name' },
                    { data: 'sem' },
                    { data: 'div' },
                    { data: 'ip' },
                    { data: null, render: function(data, type, row) { 
                        return '<button class="btn btn-sm btn-primary edit-data" data-id="'+data.enrollment_no+'">Edit</button>' +
                            '<br><button class="btn btn-sm btn-danger delete-data" data-id="'+data.enrollment_no+'">Delete</button>'; 
                    }}
                ]
            });
            $('#dataTable').on('click', '.edit-data', function(){
                $('.overlay').show();
                $('#editModal').modal('show');
                var id = $(this).data('id');
                $.ajax({
                    url: '{{ route("getSutudent") }}',
                    method: 'GET',
                    data: {
                        id: id
                    },
                    success: function(res) {
                    console.log(res);
                    $('.overlay').hide();
                    $('#enno').text(res.enrollment_no);
                    $('#edit-from').find('[name="enrollment_no"]').val(res.enrollment_no);
                    $('#edit-from').find('[name="name"]').val(res.name);
                    // $('#edit-from').find('[name="ip"]').val(res.ip);
                    $('#edit-from').find('[name="ip"]').val(res.ip);
                    $('#edit-from').find('[name="course_id"]').val(res.course_id);
                    $('#edit-from').find('[name="sem"]').val(res.sem);
                    $('#edit-from').find('[name="div"]').val(res.div);
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
            })
            $('#dataTable').on('click', '.delete-data', function(){
                $('#delete-modal').modal('show');
                var id = $(this).data('id');
                $('#delete-modal-btn').attr('data-id', id);
            })
            $('#delete-modal-btn').on('click', function(){
                var id = $(this).data('id');
                $.ajax({
                    url: '{{ route("delete-student") }}',
                    method: 'DELETE',
                    data: {
                        id: id,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        console.log(response);
                        if(response.status){
                            Toast.fire({
                                icon:'success',
                                title: "Deleted Successfully!"
                            })
                        }else{
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
                })
            })
            $('#edit-from').on('submit', function(e){
                e.preventDefault();
                Toast.fire({
                    icon: 'info',
                    title: "Request in process...",
                })
                var formData = new FormData(this);
                $.ajax({
                    url: '{{ route("update-student") }}',
                    processData: false,
                    contentType: false,
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        console.log(response);
                        if(response.status){
                            Toast.fire({
                                icon:'success',
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
                })
            })
            $('.promote-demote').on('click', function(){
                var userConfirmed = confirm("Still Are you Ready to take Risk? \n for Yes Press 'OK' \n for No press 'Cancle'");
                if (userConfirmed) {
                    var url = ($(this).data('data')=='promote')? '{{route("promote-students")}}' : '{{route("demote-students")}}';
                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: {
                            '_token': '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            Toast.fire({
                                icon: 'success',
                                title: "Promoted Successfully!"
                            })
                            setTimeout(() => {
                                location.reload();
                            }, 1000);
                            console.log(response);
                        },
                        error: function(xhr, status, error) {
                            Toast.fire({
                                icon: 'error',
                                title: "Something went wrong!"
                            })
                            console.log(error); // Optionally log the error
                        }
                    });
                }
            })
        });
    </script>
@endsection
