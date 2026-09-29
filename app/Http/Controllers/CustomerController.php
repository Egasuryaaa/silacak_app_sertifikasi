<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(['status' => 'success', 'data' => Customer::paginate(20)]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:100',
            'phone'     => 'required|string|max:20|unique:customers,phone',
            'email'     => 'nullable|email|max:100',
            'is_member' => 'boolean',
        ]);

        $customer = Customer::create($validated);
        return response()->json(['status' => 'success', 'data' => $customer], 201);
    }

    public function show(Customer $customer): JsonResponse
    {
        return response()->json(['status' => 'success', 'data' => $customer]);
    }
}