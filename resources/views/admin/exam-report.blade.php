@extends('layouts.admin_app')
@section('main')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Create Exam Report</h1>
                </div>
            </div>
        </div>
        <!-- Main content -->
        <section class="content">
            <div class="">
                <form method="get" id="report-form">
                    <div class="row justify-content-center col-12">
                        <select class="form-control col-1 mr-1" id="type" name="type">
                            <option value="Internal">Internal</option>
                            <option value="External">External</option>
                        </select>
                        <select class="form-control col-2" onchange="getSem()" name="course_id">
                            <option selected disabled>Select Course</option>
                            @foreach ($courses as $course)
                                <option value="{{ $course->id }}" data-sem="{{ $course->no_of_sem }}">{{ $course->name }}
                                </option>
                            @endforeach
                        </select>
                        <select class="form-control col-1 ml-2" name="sem" id="sem" onchange="getSubject()">
                            <option selected disabled>Sem</option>
                        </select>
                        <select class="form-control col-3 ml-2" name="subject_id" id="subject">
                            <option selected disabled>Select Subject</option>
                        </select>
                        <select class="form-control col-1 ml-2" name="div">
                            <option selected disabled>DIV</option>
                            <option val="A">A</option>
                            <option val="B">B</option>
                            <option val="C">C</option>
                            <option val="D">D</option>
                            <option val="E">E</option>
                        </select>
                        <button type="submit" class="btn btn-info col-1 ml-2" style="border-radius: 20px">Find</button>
                    </div>
                </form>
            </div>
        </section>
        <!-- /.content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row mt-2">
                    <h4 id="nodata">No Data Here!</h4>
                    <div class="card" id="card" style="display: none">
                        <div class="card-header">
                            <h3 class="card-title">Here is Report</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <!-- Dynamic headers will be appended here by JavaScript -->
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@section('js')
    <script>
        $(document).ready(function() {
            $("#report-form")[0].reset()
            Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            })
            $("#report-form").submit(function(e) {
                e.preventDefault();
                Toast.fire({
                    icon: 'info',
                    title: "Request in process...",
                });

                $.ajax({
                    url: "{{ route('genexamreport') }}",
                    type: 'get',
                    data: $("#report-form").serialize(),
                    success: function(response) {
                        if (Object.keys(response.data[0]).length > 2) {
                            $("#card").removeAttr("style");
                            $("#nodata").hide();

                            // Destroy any existing DataTable to avoid reinitialization issues
                            if ($.fn.DataTable.isDataTable("#example1")) {
                                $('#example1').DataTable().clear().destroy();
                                $('#example1 thead tr').empty();
                            }

                            // Build the table headers dynamically
                            const headerRow = $('#example1 thead tr');
                            response.columns.forEach(col => {
                                headerRow.append(`<th>${col.title}(`+$("#type").val()+`)</th>`);
                            });

                            // Prepare column definitions for DataTable
                            const columnDefs = response.columns.map(col => ({
                                data: col.title
                                .toLowerCase()
                            }));

                            // Initialize DataTable with the dynamic data and column definitions
                            $("#example1").DataTable({
                                "responsive": true,
                                "lengthChange": false,
                                "autoWidth": false,
                                "processing": true,
                                "data": response.data,
                                "columns": columnDefs,
                                "buttons": ["copy", "csv", "excel", "pdf", "print",
                                    "colvis"]
                            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
                            Toast.fire({
                                icon: 'success',
                                title: "Data fetched successfully!",
                            });
                        } else {
                            Toast.fire({
                                icon: 'warning',
                                title: "No Data found!",
                            });
                        }
                    },
                    error: function(data) {
                        console.log(data);
                        Toast.fire({
                            icon: 'error',
                            title: data.responseJSON.message
                        });
                    }
                });
            });

        })

        function getSem() {
            var course_id = $("#report-form select[name=course_id]").find(':selected').data('sem');
            $("#sem").empty();
            $("#report-form [name=subject_id]").empty();
            $("#sem").append("<option selected disabled>Select Sem</option>");
            for (var i = 1; i <= course_id; i++) {
                sem = $("#sem").append("<option value=" + i + ">" + i + "</option>");
            }
        }

        function getSubject() {
            var sem = $("#report-form select[name=sem]").find(':selected').val();
            $("#report-form [name=subject_id]").empty();
            $("#report-form [name=subject_id]").append("<option selected disabled>Select Subject</option>");

            $.ajax({
                url: '{{ route('getSubject') }}',
                type: 'GET',
                data: {
                    sem: sem,
                    course_id: $("#report-form [name=course_id]").val(),
                },
                success: function(response) {
                    $("#subject").empty().append('<option selected disabled>Select Subject</option>');
                    if (response.length > 0) {
                        response.forEach((element) => {
                            $("#subject").append(
                                `<option value="${element.id}">${element.name}</option>`);
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
        }
    </script>
@endsection
