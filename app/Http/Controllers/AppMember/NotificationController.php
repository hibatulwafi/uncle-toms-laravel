<?php

namespace App\Http\Controllers\AppMember;

use App\Http\Controllers\Controller;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{

    public function index(Request $request)
    {
        try {
            $member = $request->user('member');

            if (!$member) {
                return response()->json([
                    'message' => 'Unauthorized access: member not found.'
                ], 401);
            }

            // Ambil notifikasi yang bersifat broadcast atau milik member
            $notifications = Notification::query()
                ->with(['members' => function ($q) use ($member) {
                    $q->where('member_id', $member->id);
                }])
                ->where(function ($query) use ($member) {
                    $query->where('is_broadcast', true)
                        ->orWhereHas('members', function ($q) use ($member) {
                            $q->where('member_id', $member->id);
                        });
                })
                ->orderByDesc('created_at')
                ->get()
                ->map(function ($notification) use ($member) {
                    return [
                        'id' => $notification->id,
                        'title' => $notification->title,
                        'message' => $notification->message,
                        'type' => $notification->type,
                        'is_broadcast' => $notification->is_broadcast,
                        'is_read' => optional($notification->members->first())->pivot->is_read ?? false,
                        'created_at' => $notification->created_at->toDateTimeString(),
                    ];
                });

            return response()->json($notifications);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal memuat notifikasi.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
