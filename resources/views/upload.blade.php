<html>

<head>
    <title>Latihan FilePond</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"
        integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
</head>

<body>
    <div class="container mt-5">
        <h2>Upload Image</h2>
        <form id="addForm" method="post" action="{{route('submitImage')}}" enctype="multipart/form-data">
            <div class="mb-3">
                <input type="file" name="image" id='image' class='p-5'>
            </div>
            <div class="mb-3">
                <button type="submit" id='saveBtn' class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
    <script src="https://unpkg.com/filepond@^4/dist/filepond.js"></script>

    <script>
        //configuration filepond
        const inputElement = document.querySelector('input[id="image"]');

        // Create a FilePond instance
        const pond = FilePond.create(inputElement);

        //tujuan filepond
        FilePond.setOptions({
            server: {
                process: '{{ route('upload') }}', //upload
                revert: '{{ route('hapus') }}', //cancel
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }
        });
        //end config filepond

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
