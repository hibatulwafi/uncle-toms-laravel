<?php

namespace App\Http\Controllers\AppMember;

use App\Http\Controllers\Controller;

use App\Models\Rating;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;
use Throwable;
use Illuminate\Container\Attributes\Log;

class RatingController extends Controller
{

    public function index(Request $request): JsonResponse
    {
        $memberId = $request->user('member')->id;

        $ratings = Rating::where('member_id', $memberId)
            ->orderBy('rating_date', 'desc')
            ->get();

        return response()->json($ratings, 200);
    }

    public function store(Request $request): JsonResponse
    {
        $memberId = $request->user('member')->id;

        $validator = Validator::make($request->all(), [
            'staff_id' => 'required|exists:staffs,id',
            'transaction_id' => 'required|exists:transactions,id',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string',
            'rating_date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $rating = Rating::create([
                'member_id' => $memberId,
                'staff_id' => $request->staff_id,
                'transaction_id' => $request->transaction_id,
                'rating' => $request->rating,
                'review' => $request->review,
                'rating_date' => $request->rating_date ?? now(),
            ]);

            return response()->json(['message' => 'Rating berhasil disimpan.'], 201);
        } catch (Throwable $e) {
            // Log error untuk debugging (opsional, tapi disarankan)
            Log::error('Error creating rating: ' . $e->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan saat menyimpan rating.'], 500);
        }
    }
}
