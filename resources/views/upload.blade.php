<html>

<head>
    <title>Latihan FilePond Multiple Upload</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet" />

    <link rel='stylesheet'
        href='https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css'>
    <link rel='stylesheet' href='https://unpkg.com/filepond-plugin-file-poster/dist/filepond-plugin-file-poster.css'>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"
        integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <style>
        .filepond--drop-label {
            color: #4c4e53;
        }

        .filepond--label-action {
            text-decoration-color: #babdc0;
        }

        .filepond--panel-root {
            border-radius: 2em;
            background-color: #edf0f4;
            height: 1em;
        }

        .filepond--item-panel {
            background-color: #595e68;
        }

        .filepond--drip-blob {
            background-color: #7f8a9a;
        }

        .filepond--item {
            width: calc(20% - 0.5em);
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h2>Upload Image</h2>
        <form id="addForm" method="post" action="{{route('submitImage')}}" enctype="multipart/form-data">
            <div class="mb-3">
                <input type="file" name="image" id='image' class='p-5' multiple data-allow-reorder="true"
                data-max-file-size="3MB" data-max-files="4">
            </div>
            <div class="mb-3" style="display: flex; justify-content:end">
                <button type="submit" id='saveBtn' class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
    <script src="https://unpkg.com/filepond@^4/dist/filepond.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-poster/dist/filepond-plugin-file-poster.js"></script>

    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
    <script src='https://unpkg.com/filepond-plugin-file-encode/dist/filepond-plugin-file-encode.min.js'></script>
    <script src='https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.min.js'>
    </script>
    <script
        src='https://unpkg.com/filepond-plugin-image-exif-orientation/dist/filepond-plugin-image-exif-orientation.min.js'>
    </script>

    <script>

        FilePond.registerPlugin(
            // encodes the file as base64 data
            FilePondPluginFileEncode,

            // validates the size of the file
            FilePondPluginFileValidateSize,

            // corrects mobile image orientation
            FilePondPluginImageExifOrientation,

            FilePondPluginFilePoster,

            // previews dropped images
            FilePondPluginImagePreview
        )

        //configuration filepond
        const inputElement = document.querySelector('input[id="image"]');

        // Create a FilePond instance
        const pond = FilePond.create(inputElement);

        //tujuan filepond
        FilePond.setOptions({
            server: {
                process: '{{ route('upload') }}', //upload
                revert: (uniqueFileId, load, error) => {

                    //delete file
                    deleteImage(uniqueFileId);

                    error('Error terjadi saat delete file');

                    load();
                },
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }
        });
        //end config filepond

        function deleteImage(nameFile){
            $.ajax({
                    url: '{{ route('hapus') }}',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "DELETE",
                    data: {
                        image: nameFile
                    },
                    success: function(response) {
                        console.log(response);
                    },
                    error: function(response) {
                        console.log('error')
                    }
                });
        }

        $(document).ready(function() {
            $("#addForm").on('submit', function(e) {
                e.preventDefault();
                $("#saveBtn").html('Processing...').attr('disabled', 'disabled');
                var link = $("#addForm").attr('action');
                $.ajax({
                    url: link,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $("#saveBtn").html('Save').removeAttr('disabled');
                        pond.removeFiles(); //clear
                        alert('Berhasil')
                    },
                    error: function(response) {
                        $("#saveBtn").html('Save').removeAttr('disabled');
                        alert(response.error);
                    }
                });
            });

        });
    </script>
</body>

</html>
