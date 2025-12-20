<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(
        private UserService $userService
    ) {}

    public function search(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:2|max:255',
        ]);

        $users = $this->userService->searchUsers(
            $request->user(),
            $request->input('q')
        );

        return response()->json($users);
    }
}