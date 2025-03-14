<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}"> <!-- CSRF Token -->
    <title>Users</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

@extends('layouts.app')

@section('content')
    <div class="mb-3 d-flex justify-content-end">
        <a href="{{ route('user.create') }}" class="btn btn-primary">Create User</a>
    </div>
    @if(count($users))
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Photo</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Phone</th>
                    <th scope="col">Position ID</th>
                    <th scope="col">Registration Timestamp</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td><img src="{{ asset('storage/' . $user->photo) }}" alt="User photo"></td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->phone }}</td>
                        <td>{{ $user->position_id }}</td>
                        <td>{{ $user->registration_timestamp }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <nav aria-label="Users navigation" class="d-flex justify-content-center">
            <ul class="pagination">
                <li class="page-item"><a class="page-link" href="{{ $nextUrl }}">Show more</a></li>
            </ul>
        </nav>
    @else
        <div>No users</div>
    @endif
@endsection

</body>
</html>
