<?php

namespace App\Http\Controllers\AppMember;

use App\Http\Controllers\Controller;

use App\Models\Branch;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Container\Attributes\Log;

class BranchController extends Controller
{
    public function index(): JsonResponse
    {
        $branches = Branch::all();
        return response()->json($branches, 200);
    }

    public function show(int $id): JsonResponse
    {
        $branch = Branch::find($id);

        if (!$branch) {
            return response()->json(['message' => 'Branch not found.'], 404);
        }

        return response()->json($branch, 200);
    }
}
