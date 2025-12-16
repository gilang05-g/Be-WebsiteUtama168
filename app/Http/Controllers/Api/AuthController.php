<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // logic untuk generate member id berdasarka type member
    private function generateMemberId($type_member)
    {
        $prefix = ($type_member === 'platinum') ? '168' : '202';
        $randomNumber = rand(100000, 999999);

        return $prefix . $randomNumber;
    }

    public function register(Request $request)
    {
        try {
            $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'password_confirmation' => 'required|same:password',
            'no_telf' => 'required|string|max:13|unique:users,no_telf',
            'address' => 'required',
            'type_member' => 'in:executive,corporate,platinum',
            'role' => 'in:user,admin'
        ]);
        } catch (ValidationException $e) {
        return response()->json([
            'status' => false,
            'message' => 'Validasi gagal',
            'errors' => $e->errors(), // ⬅️ penting untuk frontend
        ], 422);
    }

        // type_member default executive
        $type = $request->type_member ?? 'executive';

        // genenerate member id secara otomatis
        $memberId = $this->generateMemberId($type);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'no_telf' => $request->no_telf,
            'address' => $request->address,
            'member_id' => $memberId,
            'type_member' => $type,
            'role' => $request->role ?? 'user',
        ]);

        return response()->json([
            'status' => 'true',
            'message' => 'Regristrasi Berhasil',
            'user' => $user
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required|min:6',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return response()->json([
                'status' => 'false',
                'message' => 'Email Tidak Di Temukan'
            ], 404);
        }

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'false',
                'message' => 'Password Salah'
            ], 401);
        }

        // Hapus semua token lama
        $user->tokens()->delete();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'true',
            'message' => 'Login Berhasil',
            'token' => $token,
            'user' => $user
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => true,
            'message' => 'Logout Berhasil'
        ]);
    }
}