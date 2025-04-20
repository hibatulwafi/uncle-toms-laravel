<?php

namespace App\Http\Controllers;

use App\Models\AppMenu;
use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class MenuAppController extends Controller
{
    public function index(): JsonResponse
    {
        $menus = AppMenu::all();
        return response()->json($menus, 200);
    }

    public function faqs(): JsonResponse
    {
        $faqs = Faq::where('is_active', true)
            ->orderBy('order')
            ->get(['question', 'answer']);

        return response()->json(['faqs' => $faqs], 200);
    }

    public function support(): JsonResponse
    {
        $supportData = [
            'support' => [
                [
                    'title' => 'email',
                    'icon' => 'lorem',
                    'value' => 'support@uncletoms.com',
                ],
                [
                    'title' => 'wa',
                    'icon' => 'lorem',
                    'value' => '081313',
                ],
                [
                    'title' => 'address',
                    'icon' => 'lorem',
                    'value' => 'lorem',
                ],
            ],
        ];

        return response()->json($supportData, 200);
    }
}
