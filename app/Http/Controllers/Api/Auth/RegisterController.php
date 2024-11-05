<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Tymon\JWTAuth\Facades\JWTAuth;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        // Custom messages
        $messages = [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password harus minimal :min karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ];

        // Validasi input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ], $messages);

        // Response error validasi
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Membuat user baru
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Menetapkan peran dengan role_id = 2
        $role = Role::find(2); // Atau gunakan Role::where('name', 'nama_role')->first();
        if ($role) {
            $user->assignRole($role);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Role not found'
            ], 400);
        }

        // Generate token untuk user yang terdaftar
        if (!$token = JWTAuth::fromUser($user)) {
            return response()->json([
                'success' => false,
                'message' => 'Could not create token'
            ], 500);
        }

        // Response register "success" dengan token
        return response()->json([
            'success' => true,
            'user' => $user->only(['name', 'email']),
            'token' => $token
        ], 201);
    }
}
