@extends('layouts.admin_app')
@section('main')
<div class="row justify-content-center " style="min-height: 100vh;">
    <form action="" method="post" class="col-md-8" enctype="multipart/form-data">
        <h4 class="col-6">Add Bulk Students</h4>
        <div class="col-md-12">
            <div class="card card-default" style="padding: 20px;">
                <label for="my-select">Upload CSV</label>
                <input type="file" id="file" name="file" class="form-control" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" />
                <div class="mt-4">
                    <label for="my-select">Stream</label>
                    <select id="my-select" name="stream" class="form-control">
                        @foreach ($streams as $stream)
                            <option value="{{ $stream->id }}">{{ $stream->stream }}</option>
                        @endforeach
                    </select>
                    <label for="my-select">Year</label>
                    <select id="my-select" name="year" class="form-control">
                        <option value="FY">FY</option>
                        <option value="SY">SY</option>
                        <option value="TY">TY</option>
                    </select>
                </div>
                <button class="btn btn-warning mt-2">Submit</button>
            </div>
            @error('file')
                <p>{{ $message }}</p>
            @enderror
            @error('year')
                <p>{{ $message }}</p>
            @enderror
            @error('stream')
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
@endsection

@section('js')
    <script>
    </script>
@endsection
