<?php
namespace App\Http\Controllers\AppMember;

use App\Http\Controllers\Controller;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function profile(Request $request): JsonResponse
    {
        // $request->user('member') akan berisi instance member yang terautentikasi
        $member = $request->user('member');

        // Anda bisa memilih data profil mana saja yang ingin Anda kembalikan
        return response()->json([
            'id' => $member->id,
            'photo' => $member->photo,
            'name' => $member->name,
            'email' => $member->email,
            'phone' => $member->phone,
            'total_point' => $member->total_point,
            'birth_date' => $member->birth_date,
            'gender' => $member->gender,
            'address' => $member->address,
            'is_email_verified' => $member->is_email_verified,
            'is_phone_verified' => $member->is_phone_verified,
            'created_at' => $member->created_at,
            'updated_at' => $member->updated_at,
        ], 200);
    }
}
