<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        try {
            $member = $request->user('member');

            if (!$member) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }

            $bookings = Booking::with(['service', 'branch'])
                ->where('member_id', $member->id)
                ->orderByDesc('booking_date')
                ->orderByDesc('booking_time')
                ->get();

            return response()->json($bookings);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal memuat booking.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $member = $request->user('member');

            if (!$member) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }

            $validator = Validator::make($request->all(), [
                'service_id' => 'required|exists:services,id',
                'booking_date' => 'required|date',
                'booking_time' => 'required|date_format:H:i',
                'branch_id' => 'nullable|exists:branches,id',
                'notes' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $booking = Booking::create([
                'member_id' => $member->id,
                'service_id' => $request->service_id,
                'branch_id' => $request->branch_id,
                'booking_date' => $request->booking_date,
                'booking_time' => $request->booking_time,
                'notes' => $request->notes,
                'status' => 'pending',
            ]);

            return response()->json([
                'message' => 'Booking berhasil dibuat.',
                'data' => $booking
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal menyimpan booking.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
