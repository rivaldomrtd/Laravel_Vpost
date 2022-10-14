<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\ApiResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        //get posts
        $user = User::latest()->paginate(5);
        //return collection of posts as a resource
        return new ApiResource(true, 'List Data User', $user);
    }

    public function store(Request $request)
    {
        //define validation rules
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', Rules\Password::defaults()],
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        //create post
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        //return response
        return new ApiResource(true, 'Data User Berhasil Ditambahkan!', $user);
    }

    public function show(User $user)
    {
        //return single post as a resource
        return new ApiResource(true, 'Data User Ditemukan!', $user);
    }

    public function update(Request $request, User $user)
    {
        //define validation rules
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required',  Rules\Password::defaults()],
        ]);
        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);


        //return response
        return new ApiResource(true, 'Data User Berhasil Diubah!', $user);
    }
    public function destroy(User $user)
    {
        //delete post
        $user->delete();
        //return response
        return new ApiResource(true, 'Data User Berhasil Dihapus!', null);
    }
}
