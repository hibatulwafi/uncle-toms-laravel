<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CapsterController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        // Mulai dengan query untuk mengambil semua capster (Staff)
        $query = Staff::query();

        // Filter berdasarkan nama capster (jika ada parameter 'name' di request)
        if ($request->has('name') && !empty($request->name)) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        // Filter berdasarkan branch_id (jika ada parameter 'branch_id' di request)
        if ($request->has('branch_id') && !empty($request->branch_id)) {
            $query->where('branch_id', $request->branch_id);
        }

        // Filter berdasarkan status (jika ada parameter 'status' di request)
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        // Eksekusi query dan ambil hasilnya
        $capsters = $query->get();

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
