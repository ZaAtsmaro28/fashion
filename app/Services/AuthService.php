<?php

// app/Services/AuthService.php
namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function login($credentials)
    {
        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Kredensial yang Anda berikan salah.'],
            ]);
        }

        // Buat token dengan kemampuan (abilities) berdasarkan role
        $token = $user->createToken('auth_token', [$user->role])->plainTextToken;

        return [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ],
            'access_token' => $token,
            'token_type' => 'Bearer',
        ];
    }

    public function logout($user)
    {
        // Hapus token yang sedang digunakan saat ini
        return $user->currentAccessToken()->delete();
    }
}
