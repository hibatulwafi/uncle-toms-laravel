<?php

namespace App\Http\Controllers\AuthMember;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\JsonResponse;

class AuthMemberController extends Controller
{

    public function loginMember(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $member = Member::where('email', $request->email)->first();

        // Periksa apakah email tidak terdaftar
        if (!$member) {
            return response()->json([
                'message' => 'Email tidak terdaftar.',
            ], 404); // Kode status 404 Not Found lebih sesuai untuk "tidak ditemukan"
        }

        // Periksa apakah password salah
        if (!Hash::check($request->password, $member->password)) {
            return response()->json([
                'message' => 'Email atau kata sandi salah.',
            ], 401); // Kode status 401 Unauthorized untuk kredensial yang salah
        }

        $member->tokens()->delete();
        $token = $member->createToken('member_auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 200);
    }

    public function registerMember(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:members',
            'password' => 'required|string|min:8|confirmed',
            // Tambahkan validasi untuk field lain jika diperlukan
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $member = Member::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            // Isi field lain sesuai kebutuhan
        ]);

        event(new Registered($member)); // Jika Anda mengaktifkan verifikasi email

        $token = $member->createToken('member_auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'message' => 'Registrasi berhasil. Silakan verifikasi email Anda jika diperlukan.',
        ], 201);
    }

    public function verifyEmail(Request $request, $id, $hash)
    {
        $member = Member::findOrFail($id);

        if (!hash_equals((string) $hash, sha1($member->email))) {
            return response()->json(['message' => 'Tautan verifikasi tidak valid.'], 400);
        }

        if ($member->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email Anda sudah terverifikasi.'], 200);
        }

        $member->markEmailAsVerified();
        event(new \Illuminate\Auth\Events\Verified($member));

        return response()->json(['message' => 'Email Anda berhasil diverifikasi.'], 200);
    }

    public function sendVerificationEmail(Request $request)
    {
        if ($request->user('member')->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email Anda sudah terverifikasi.'], 200);
        }

        $request->user('member')->sendEmailVerificationNotification();

        return response()->json(['message' => 'Tautan verifikasi email telah dikirim.'], 200);
    }

    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        Password::broker('members')->sendResetLink(
            $request->only('email')
        );

        return response()->json(['message' => 'Tautan reset password telah dikirim ke email Anda.'], 200);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        $response = Password::broker('members')->reset(
            $request->only('email', 'password', 'token'),
            function ($user, $password) {
                $user->password = Hash::make($password);
                $user->save();
            }
        );

        if ($response === Password::PASSWORD_RESET) {
            return response()->json(['message' => 'Password Anda berhasil direset.'], 200);
        } else {
            return response()->json(['message' => 'Gagal mereset password. Token tidak valid atau email tidak ditemukan.'], 400);
        }
    }

    public function logoutMember(Request $request): JsonResponse
    {
        $request->user('member')->currentAccessToken()->delete();
        return response()->json(['message' => 'Logout berhasil.'], 200);
    }
}
