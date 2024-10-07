@extends('layouts.admin_app')
@section('main')
{{-- @dd($streams) --}}
    <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div class="row mt-2">
                <div class="col-12">
                    <form method="POST" id="add-steam">
                        @csrf
                        <div class="card card-default" style="padding: 20px;">
                            <label>Add Stream</label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" name="stream" placeholder="BScIT" />
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit"><i class="fas fa-chart-bar"></i> Add Stream</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <hr>
                    <div class="card card-default" style="padding: 20px;">
                      <center> <h3>Subject Add</h3></center>
                        <form method="POST" id="add-subject">
                          @csrf
                            <label for="stream">Select Stream</label>
                            <select id="stream" class="form-control select2" multiple="multiple" name="stream[]" data-placeholder="Select a Stream">
                              @foreach ($streams as $stream)
                                <option value="{{$stream->id}}">{{$stream->stream}}</option>
                              @endforeach
                            </select>
                            <label for="sem">Select Sem</label>
                            <select id="sem" class="form-control" name="sem">
                                <option value="1">1st</option>
                                <option value="2">2nd</option>
                                <option value="3">3rd</option>
                                <option value="4">4th</option>
                                <option value="5">5th</option>
                                <option value="6">6th</option>
                            </select>
                            <label for="Subject">Add Subject</label>
                            <input type="text" id="Subject" class="form-control" name="subject" placeholder="Subject" />
                            <button class="btn btn-success mt-2" type="submit"><i class="fas fa-book"></i> Add Subject</button>
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
                          <th scope="col">Student Name</th>
                          <th scope="col">Year</th>
                          <th scope="col">Sub. Name</th>
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
            <h4 class="modal-title">Default Modal</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form method="post" id="edit-from">
            <div class="modal-body">
              @csrf
              <input type="hidden" name="id" placeholder="Name" />
              <label class="control-label">Stream</label>
              <select class="form-control select2" name="stream[]" data-placeholder="Select a Stream">
                @foreach ($streams as $stream)
                  <option value="{{$stream->id}}">{{$stream->stream}}</option>
                @endforeach
              </select>
              <label class="control-label">Sem</label>
              <select class="form-control" name="sem">
                <option value="1">1st</option>
                <option value="2">2nd</option>
                <option value="3">3rd</option>
                <option value="4">4th</option>
                <option value="5">5th</option>
                <option value="6">6th</option>
              </select>
              <label class="control-label">Sub. Name</label>
              <input type="text" name="subject" class="form-control" placeholder="Name" />
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

    $('#dataTable').on('click', '.edit-data', function() {
      $('.overlay').show();
      $('#lodingModal').modal('show');
      var id = $(this).data('id');
      $.ajax({
        url: '{{ route("getSubject") }}',
        method: 'GET',
        data: {
          id: id
        },
        success: function(res) {
          console.log(res);
          $('.overlay').hide();
          $('#edit-from').find('[name="id"]').val(res.id);
          $('#edit-from').find('[name="subject"]').val(res.name);
          $('#edit-from').find('[name="year"]').val(res.year);
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
        "lengthChange": false,
        "autoWidth": true,
        "responsive": true,
        ajax: {
          url : '{{route("getSubjects")}}',
          dataSrc: 'data'
        },
        columns: [
            { data: null, render: function(data, type, row, meta) { return meta.row + meta.settings._iDisplayStart + 1; } }, // Index column
            { data: 'name' },
            { data: 'year', render: function(data) { return (data == 1) ? 'FY' : (data == 2) ? 'SY' : 'TY'; } },
            { data: 'name' },
            { data: null, render: function(data, type, row) { return '<button class="btn btn-sm btn-primary edit-data" data-id="'+data.id+'">edit</button><br><button class="btn btn-sm btn-danger delete-data" data-id="'+data.id+'">Delete</button>'; } } // Action column
        ]
    });
    $('#add-steam').on('submit', function(e) {
        e.preventDefault();
        Toast.fire({
          icon: 'info',
          title: "Request in process...",
        })
        var formData = new FormData(this);
        $.ajax({
          url: '{{ route('add-stream') }}',
          processData: false,
          contentType: false,
          method: 'POST',
          data: formData,
          success: function(response) {
            console.log(response);
            if(response.status){
              Toast.fire({
                icon: 'success',
                title: "Added Successfully!"
              })
            }else{
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
        url: '{{ route("add-subject") }}',
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
    $('#delete-modal-btn').on('click', function(){
      var id = $(this).data('id');
      $.ajax({
        url: '{{ route("delete-subject") }}',
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
      });
    });
    $('#edit-from').on('submit', function(e){
      e.preventDefault();
      Toast.fire({
        icon: 'info',
        title: "Request in process...",
      })
      var formData = new FormData(this);
      $.ajax({
        url: '{{ route("add-subject") }}',
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
    });
  });
</script>
@endsection
