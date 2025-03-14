<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}"> <!-- CSRF Token -->
    <title>Main Page</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-center">
    <h1>Hello, this is test app</h1>
</div>
@endsection

</body>
</html>
