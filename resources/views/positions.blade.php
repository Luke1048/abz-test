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
    @if(count($positions))
        @foreach ($positions as $position)
            <div class="mb-3">{{ $position->name }}</div>
        @endforeach
    @else
        <div>No positions</div>
    @endif
@endsection

</body>
</html>
