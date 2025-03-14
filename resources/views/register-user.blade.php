<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}"> <!-- CSRF Token -->
    <title>Registar User</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

@extends('layouts.app')

@section('content')

<div id="response-message"></div>

<form id="user-form" class="mb-3">
    @csrf
    <div class="mb-3">
        <label for="name" class="form-label">Name:</label>
        <input type="text" class="form-control" name="name" id="name" placeholder="Enter Name" value="{{ old('name') }}" required>
    </div>
    <div id="name-error"></div>

    <div class="mb-3">
        <label for="email" class="form-label">Email:</label>
        <input type="email" class="form-control" name="email" id="email" placeholder="Enter Email" value="{{ old('email') }}" required>
    </div>
    <div id="email-error"></div>

    <div class="mb-3">
        <label for="phone" class="form-label">Phone:</label>
        <input type="tel" class="form-control" name="phone" id="phone" placeholder="Enter Phone" value="{{ old('phone') }}" required>
    </div>
    <div id="phone-error"></div>

    <div class="mb-3">
        <label for="position" class="form-label">Position:</label>
        <select class="form-select" name="position" id="position" required>
            <option disabled>Select Position</option>
            @foreach ($positions as $position)
                <option value="{{ $position["id"] }}" {{ old('position') == $position["id"] ? 'selected' : '' }}>{{ $position["name"] }}</option>
            @endforeach
        </select>
    </div>
    <div id="position-error"></div>

    <div class="mb-3">
        <label for="photo" class="form-label">Upload Photo:</label>
        <input type="file" class="form-control" name="photo" id="photo" accept="image/*" required>
    </div>
    <div id="photo-error"></div>

    <div class="mb-3">
        <label for="user_registration_token" class="form-label">User Registration Token:</label>
        <input type="text" class="form-control" name="user_registration_token" id="user_registration_token" placeholder="Enter User Registration Token" value="{{ old('user_registration_token') }}" required>
    </div>
    <div id="user-registration-token-error"></div>

    <div class="d-flex justify-content-end">
        <button type="submit" class="btn btn-primary">Register User</button>
    </div>
</form>
@endsection

<script>
$(document).ready(function () {
    $('#user-form').submit(function (event) {
        event.preventDefault();

        $('#name-error').removeClass('mb-3 p-3 bg-danger text-white d-flex align-items-center');
        $('#email-error').removeClass('mb-3 p-3 bg-danger text-white d-flex align-items-center');
        $('#phone-error').removeClass('mb-3 p-3 bg-danger text-white d-flex align-items-center');
        $('#position-error').removeClass('mb-3 p-3 bg-danger text-white d-flex align-items-center');
        $('#photo-error').removeClass('mb-3 p-3 bg-danger text-white d-flex align-items-center');
        $('#user-registration-token-error').removeClass('mb-3 p-3 bg-danger text-white d-flex align-items-center');

        let accessToken = $('#user_registration_token').val();
        let formData = new FormData(this);

        $.ajax({
            url: '{{ route('user.store') }}',
            type: 'POST',
            headers: {
                'Authorization': 'Bearer ' + accessToken,
            },
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                window.history.replaceState({}, document.title, window.location.pathname);

                $('#response-message').text(response.message);
                $('#response-message').removeClass('mb-3 p-3 bg-success bg-danger text-white d-flex align-items-center');
                $('#response-message').addClass('mb-3 p-3 bg-success text-white d-flex align-items-center');

                $('#name').val('');
                $('#email').val('');
                $('#phone').val('');
                $('#position').val('');
                $('#photo').val('');
                $('#user_registration_token').val('');
            },
            error: function (xhr) {
                window.history.replaceState({}, document.title, window.location.pathname);

                $('#response-message').text(xhr.responseJSON.message);
                $('#response-message').removeClass('mb-3 p-3 bg-success bg-danger text-white d-flex align-items-center');
                $('#response-message').addClass('mb-3 p-3 bg-danger text-white d-flex align-items-center');

                console.log('error')
                console.log(xhr.responseJSON.fails)

                if ('name' in xhr.responseJSON.fails) {
                    $('#name-error').text(xhr.responseJSON.fails.name);
                    $('#name-error').addClass('mb-3 p-3 bg-danger text-white d-flex align-items-center');
                }
                if ('email' in xhr.responseJSON.fails) {
                    $('#email-error').text(xhr.responseJSON.fails.email);
                    $('#email-error').addClass('mb-3 p-3 bg-danger text-white d-flex align-items-center');
                }
                if ('phone' in xhr.responseJSON.fails) {
                    $('#phone-error').text(xhr.responseJSON.fails.phone);
                    $('#phone-error').addClass('mb-3 p-3 bg-danger text-white d-flex align-items-center');
                }
                if ('position' in xhr.responseJSON.fails) {
                    $('#phopositionne-error').text(xhr.responseJSON.fails.position);
                    $('#position-error').addClass('mb-3 p-3 bg-danger text-white d-flex align-items-center');
                }
                if ('photo' in xhr.responseJSON.fails) {
                    $('#photo-error').text(xhr.responseJSON.fails.photo);
                    $('#photo-error').addClass('mb-3 p-3 bg-danger text-white d-flex align-items-center');
                }
                if ('user-registration-token-error' in xhr.responseJSON.fails) {
                    $('#user-registration-token-error-error').text(xhr.responseJSON.fails.photo);
                    $('#user-registration-token-error-error').addClass('mb-3 p-3 bg-danger text-white d-flex align-items-center');
                }
            }
        });
    });
});
</script>

</body>
</html>
