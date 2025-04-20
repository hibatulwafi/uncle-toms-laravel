<?php

namespace App\Http\Controllers;

use App\Models\AppVersion;
use App\Models\Onboarding;
use Illuminate\Http\Request;

class AppController extends Controller
{
    public function getLatestAppVersion(Request $request)
    {
        $platform = strtolower($request->header('X-Platform', 'all'));
        $appVersion = AppVersion::where('platform', $platform)
            ->orWhere('platform', 'all')
            ->orderByDesc('created_at')
            ->first();

        if ($appVersion) {
            return response()->json(['min_version' => $appVersion->min_version]);
        } else {
            return response()->json(['error' => 'Versi aplikasi tidak ditemukan'], 404);
        }
    }

    public function onboarding()
    {
        $onboardingData = Onboarding::where('is_active', 1)
            ->orderBy('index')
            ->get([
                'id',
                'index',
                'img',
                'deskripsi',
                'title'
            ]);

        return response()->json($onboardingData, 200);
    }
}
