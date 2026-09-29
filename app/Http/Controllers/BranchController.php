<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(['status' => 'success', 'data' => Branch::all()]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'code'    => 'required|string|max:10|unique:branches,code',
            'name'    => 'required|string|max:100',
            'city'    => 'required|string|max:100',
            'address' => 'nullable|string',
        ]);

        $branch = Branch::create($validated);
        return response()->json(['status' => 'success', 'data' => $branch], 201);
    }

    public function show(Branch $branch): JsonResponse
    {
        return response()->json(['status' => 'success', 'data' => $branch]);
    }
}