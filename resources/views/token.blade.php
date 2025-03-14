<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}"> <!-- CSRF Token -->
    <title>Token</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

@extends('layouts.app')

@section('content')
<div class="mb-3 d-flex justify-content-end">
    <button id="get_token_button" class="btn btn-info">Get Token</button>
</div>
<div class="mb-3">
    <input type="text" class="form-control" id="token">
</div>
<div class="mb-3 d-flex justify-content-end">
    <button id="copy_token" class="btn btn-success d-none">Copy Token</button>
</div>
@endsection

<script>
$(document).ready(function () {
    $('#get_token_button').click(function() {
        $.ajax({
            url: '{{ route('token') }}',
            type: 'GET',
            headers: {
            },
            success: function (response) {
                $('#token').val(response.token);
                $('#copy_token').removeClass('d-none');
            },
            error: function (xhr) {
                console.log('error')
                console.log(xhr.responseJSON.fails)
            }
        });
    });

    $('#copy_token').click(function() {
        let tokenValue = $('#token').val()

        navigator.clipboard.writeText(tokenValue).then(function() {
            alert('Token copied to clipboard!');
        }).catch(function(error) {
            alert('Failed to copy text: ' + error);
        });
    });
});
</script>

</body>
</html>
