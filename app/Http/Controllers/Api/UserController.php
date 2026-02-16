<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserController extends Controller
{
    public function index()
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
    foreach ($request->users as $user) {
        User::create([
            'name' => $user['name'],
            'email' => $user['email'],
            'password' => bcrypt($user['password'] ?? '123456')
        ]);
    }

    return response()->json(['status'=>'ok']);
}
    public function update(Request $request, $id)
{
    $user = User::findOrFail($id);
    $user->update($request->only('name','email'));

    return response()->json(['status'=>'updated']);
}


}