<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function get_users()
    {
        return response()->json([
            'users' => User::select('id','name','email')->get(),
            'is_admin' => Auth::check() ? Auth::user()->is_admin : 0
        ]);
    } 

    public function store(Request $request)
{
    $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6'
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'is_admin' => 0
    ]);

    return response()->json([
        'success' => true,
        'user' => $user
    ]);
}



public function bulkStore(Request $request)
{
    DB::transaction(function () use ($request) {

        foreach ($request->users as $user) {

            // Skip empty rows
            if (empty($user['name']) || empty($user['email'])) {
                continue;
            }

            // Validate duplicate email for new users
            if (empty($user['id'])) {
                if (User::where('email', $user['email'])->exists()) {
                    throw ValidationException::withMessages([
                        'email' => "Email {$user['email']} already exists."
                    ]);
                }
            }

            $userModel = null;

            if (!empty($user['id'])) {
                $userModel = User::find($user['id']);
            }

            if ($userModel) {
                // UPDATE
                $userModel->update([
                    'name' => $user['name'],
                    'email' => $user['email']
                ]);
            } else {
                // CREATE
                User::create([
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'password' => bcrypt($user['password'] ?? '123456')
                ]);
            }

        }

    });

    return response()->json(['message' => 'Users saved successfully']);
}

   


}