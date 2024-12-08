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
                        <h3 class="card-title">Lab Title: <strong>{{ $labData->title }}</strong></h3>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h5 class="font-weight-bold">Lab Description</h5>
                                <p class="text-muted">{!! $labData->dec !!}</p>
                            </div>
                        </div>
                        @if ($labData->file_path)
                            <a href="{{ url(Storage::url($labData->file_path)) }}" target="_blank" class="btn btn-light mb-3">
                                <i class="fas fa-download"></i> Download File
                            </a>
                        @endif
                        @if($labinfo)
                            <div class="alert alert-info" style="font-size: 12px">Your assignment has already been submitted. If you wish to update it, you can upload a new file, which will replace the existing one.</div>
                        @endif
                        <!-- Dropzone for File Upload -->
                        <form action="{{ route('upload.assignment') }}" method="POST" enctype="multipart/form-data" class="form" id="assignment-form">
                            @csrf
                            <div class="fallback">
                                <input type="file" name="file" id="file-input" style="display: none;" accept=".pdf,.zip,.txt,.doc,.docx" required>
                            </div>
                            <div id="drop-zone" class="border p-4 text-center" style="margin: 2%; border: 2px dashed #cccccc;">
                                <p id="file-list">Drag & Drop file here or click to upload a file</p>
                                <p class="text-muted">Only PDF, ZIP, TXT, DOC, and DOCX files are allowed.</p>
                            </div>
                            <div class="dz-message text-center"></div>
                            <button type="submit" class="btn btn-success col-12 mt-3" id="submit-all">
                                <i class="fas fa-upload"></i> Submit
                            </button>
                            <div class="progress mt-3" style="display: none;">
                                <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer text-muted text-right">
                        <small>Due Date: <span id="last-updated">{{ \Carbon\Carbon::parse($labData->EndTime)->format('Y-m-d h:i A')}}</span></small>
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
    $('#assignment-form')[0].reset();
    Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
    })

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

    // Remove highlight when dragging leaves
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
            // Use a DataTransfer object to simulate setting the input field
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            fileInput.files = dataTransfer.files; // Set the file input's files property
        }
    }

    // Clear the selected file and reset the drop zone
    function clearFile() {
        selectedFile = null;
        fileInput.value = '';
        fileList.innerHTML = '<p id="file-list">Drag & Drop file here or click to upload a file</p>';
    }
    
    $('#assignment-form').on('submit', function (event) {
        event.preventDefault();
        $("#submit-all").attr('disabled', 'disabled');

        var formData = new FormData(this);

        $('.progress').show();

        // AJAX request
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            xhr: function () {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener("progress", function (evt) {
                    if (evt.lengthComputable) {
                        var percentComplete = evt.loaded / evt.total * 100;
                        $('.progress-bar').css('width', percentComplete + '%');
                        $('.progress-bar').attr('aria-valuenow', percentComplete);
                        $('.progress-bar').text(Math.round(percentComplete) + '%');
                    }
                }, false);
                return xhr;
            },
            success: function (response) {
                Toast.fire({
                    icon: 'success',
                    title: "Submited Successfully!"
                })
                setTimeout(() => {
                    location.reload();
                }, 2000);
            },
            error: function (err) {
                $("#submit-all").removeAttr('disabled');
                console.log(err);
                Toast.fire({
                    icon: 'error',
                    title: err.responseJSON.error ? err.responseJSON.error : err.responseJSON.message
                })
            }
        });
    });
});
</script>
@endsection