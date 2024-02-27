<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::latest()->paginate(10);
        return [
            "status" => 1,
            "data" => $users
        ];
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'fullname' => 'required|string',
            'email' => 'required|email|unique:users',
            'cpf' => 'required|string|unique:users',
            'password' => 'required|string'
        ]);
        $user = User::create([
            'fullname' => $request->fullname,
            'email' => $request->email,
            'cpf' => $request->cpf,
            'password' => password_hash($request->password, PASSWORD_BCRYPT)
        ]);
        return [
            'status' => 1,
            'data' => $user
        ];
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return [
            'status' => 1,
            'data' => $user
        ];
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'fullname' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'cpf' => 'required|string|unique:users,cpf,' . $user->id,
            'password' => 'required|string'
        ]);
        $user->update([
            'fullname' => $request->fullname,
            'email' => $request->email,
            'cpf' => $request->cpf,
            'password' => password_hash($request->password, PASSWORD_BCRYPT)
        ]);
        return [
            'status' => 1,
            'data' => $user
        ];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return [
            'status' => 1,
            'data' => null
        ];
    }
}
