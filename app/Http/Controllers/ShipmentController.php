<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShipmentController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Shipment::with(['originBranch', 'destBranch', 'customer']);

        if ($request->filled('status')) {
            $query->where('current_status', $request->status);
        }

        if ($request->filled('branch_id')) {
            $query->where('origin_branch_id', $request->branch_id);
        }

        $shipments = $query->orderBy('created_at', 'desc')->paginate(25);

        return response()->json([
            'status' => 'success',
            'data'   => $shipments,
        ]);
    }

    public function show(string $id): JsonResponse
    {
        $shipment = Shipment::with([
            'originBranch',
            'destBranch',
            'customer',
            'trackingHistories' => fn($q) => $q->orderBy('recorded_at', 'desc'),
        ])->where('id', $id)
          ->orWhere('tracking_number', $id)
          ->firstOrFail();

        return response()->json([
            'status' => 'success',
            'data'   => $shipment,
        ]);
    }
}