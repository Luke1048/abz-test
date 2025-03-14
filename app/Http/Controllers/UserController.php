<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Position;

class UserController extends Controller
{
    public function index(Request $request) {
        $validated = $request->validate([
            'page' => ['nullable', 'integer', 'min:1'],
            'count' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        $count = $validated['count'] ?? 6;
        $users = User::with('position')->paginate($count);

        $firstPageUrl = $users->url(1) . '&count=' . $count;
        $nextUrl = $users->nextPageUrl() ? $users->nextPageUrl() . '&count=' . $count : $firstPageUrl;

        return view('users', compact('users', 'nextUrl'));
    }

    public function create() {
        $positions = Position::all();

        return view('register-user', compact('positions'));
    }
}
