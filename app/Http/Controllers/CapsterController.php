<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CapsterController extends Controller
{
    public function index(): JsonResponse
    {
        $capsters = Staff::all();
        return response()->json($capsters, 200);
    }

    public function show(int $id): JsonResponse
    {
        $capster = Staff::find($id);

        if (!$capster) {
            return response()->json(['message' => 'Staff not found.'], 404);
        }

        return response()->json($capster, 200);
    }
}
