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
    border: 2px dashed #007bff; /* Primary border color */
    border-radius: 10px;
    background: #f9f9f9;
    padding: 20px;
    transition: border-color 0.3s ease;
}
.dropzone.dz-clickable:hover {
    border-color: #0056b3; /* Darker shade on hover */
}
.dropzone .dz-message {
    font-size: 1.2em;
    color: #6c757d; /* Secondary text color */
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
            <div class="col-md-12 mt-5">
                <!-- Big Card for Lab Information -->
                <div class="card shadow-lg border-0 rounded-lg">
                    <div class="card-header bg-primary text-white">
                        <h3 class="card-title">Lab Title: <strong>{{ session('lab')->title }}</strong></h3>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h5 class="font-weight-bold">Lab Description</h5>
                                <p class="text-muted">{!! session('lab')->dec !!}</p>
                            </div>
                        </div>
                        <a href="{{ url(Storage::url(session('lab')->file_path)) }}" target="_blank" class="btn btn-light mb-3">
                            <i class="fas fa-download"></i> Download File
                        </a>

                        <!-- Dropzone for File Upload -->
                        <form action="{{ route('upload.assignment') }}" method="POST" class="dropzone" id="my-awesome-dropzone">
                            @csrf
                            <div class="fallback">
                                <input name="file" type="file" multiple style="display: none;" />
                            </div>
                            <div class="dz-message text-center">
                                <h4 class="mb-3">Drag and drop files here or click to upload</h4>
                                <p class="text-muted">Only PDF, ZIP, TXT, DOC, and DOCX files are allowed.</p>
                            </div>
                        </form>
                        <button type="button" class="btn btn-success col-12 mt-3" id="submit-all">
                            <i class="fas fa-upload"></i> Submit
                        </button>
                    </div>
                    <div class="card-footer text-muted text-right">
                        <small>Due Date: <span id="last-updated">{{ \Carbon\Carbon::parse(session('lab')->EndTime)->format('Y-m-d h:i A')}}</span></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('js')
<script src="{{url('plugins/dropzone/min/CFdropzone.min.js')}}"></script>
<script>
$(document).ready(function() {
    Dropzone.options.myAwesomeDropzone = {
        paramName: "file", // The name that will be used to transfer the file
        maxFilesize: 100, // MB - maximum size for individual files
        acceptedFiles: ".pdf,.doc,.docx,.zip,.txt",
        dictDefaultMessage: "Drag and drop files here or click to upload",
        maxFiles: 1, // Allow only one file to upload at a time
        autoProcessQueue: false, // Prevent automatic upload on file addition

        init: function() {
            var myDropzone = this;
            let totalFileSize = 0; // Track total size of uploaded files

            // Handle file addition
            this.on("addedfile", function(file) {
                // If there's already a file, remove it
                if (myDropzone.files.length > 1) {
                    myDropzone.removeFile(myDropzone.files[0]); // Remove the first file
                }

                totalFileSize = file.size / 1024 / 1024; // Set total size to the current file size

                // Check total file size
                if (totalFileSize > 100) { // If total exceeds 100 MB
                    this.removeFile(file); // Remove the file
                    alert("Total file size cannot exceed 100 MB.");
                    totalFileSize = 0; // Reset total size
                }
            });

            // Handle file removal
            this.on("removedfile", function(file) {
                totalFileSize = 0; // Reset size when file is removed
            });

            // Using jQuery to handle the submit button click
            $('#submit-all').on('click', function(e) {
                e.preventDefault(); // Prevent the default action

                // Check if there are files to upload
                if (myDropzone.files.length === 0) {
                    alert("Please add a file to upload.");
                    return;
                }

                // Start uploading files
                myDropzone.processQueue(); // Tell Dropzone to upload the file
            });

            // Handle the success response
            this.on("success", function(file, response) {
                console.log("File uploaded successfully:", response);
                // Clear the Dropzone after successful upload
                myDropzone.removeAllFiles();
            });

            // Handle error
            this.on("error", function(file, response) {
                console.error("Upload error:", response);
                alert("Error uploading file: " + response);
            });
        }
    };
});
</script>
@endsection