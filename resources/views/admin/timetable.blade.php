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
                                data-placeholder="Select a Cource">
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
                            <select type="text" id="Subject" class="form-control" name="subject_id" placeholder="Subject">
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
            </div>
        </div>
    </section>
@endsection
@section('js')
    <script>
        $(document).ready(function() {
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
                        cource_id: courseDropdown.val(),
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
                e.preveDefault();
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
        })
    </script>
@endsection
