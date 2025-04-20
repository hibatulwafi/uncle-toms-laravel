<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(): JsonResponse
    {
        $services = Service::all();
        return response()->json($services, 200);
    }

    public function show(int $id): JsonResponse
    {
        $services = Service::find($id);

        if (!$services) {
            return response()->json(['message' => 'Service not found.'], 404);
        }

        return response()->json($services, 200);
    }
}
