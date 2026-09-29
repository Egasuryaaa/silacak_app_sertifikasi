<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use App\Models\TrackingHistory;
use App\Repositories\ShipmentTrackingRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrackingHistoryController extends Controller
{
    public function __construct(
        protected ShipmentTrackingRepository $trackingRepo
    ) {}

    public function index(Request $request): JsonResponse
    {
        $request->validate(['tracking_number' => 'required|string']);

        $shipment = Shipment::where('tracking_number', $request->tracking_number)->firstOrFail();
        $histories = $shipment->trackingHistories()->with('branch')->orderBy('recorded_at', 'asc')->get();

        return response()->json([
            'status' => 'success',
            'data'   => $histories,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'shipment_id' => 'required|exists:shipments,id',
            'branch_id'   => 'nullable|exists:branches,id',
            'status'      => 'required|string|in:MANIFEST,ON_TRANSIT,OUT_FOR_DELIVERY,DELIVERED',
            'description' => 'required|string|max:255',
        ]);

        $history = DB::transaction(function () use ($validated) {
            $shipment = Shipment::findOrFail($validated['shipment_id']);

            $newHistory = TrackingHistory::create([
                'shipment_id' => $shipment->id,
                'branch_id'   => $validated['branch_id'] ?? null,
                'status'      => $validated['status'],
                'description' => $validated['description'],
                'recorded_at' => now(),
            ]);

            $shipment->update(['current_status' => $validated['status']]);

            // Invalidation cache Redis
            $this->trackingRepo->invalidateCache($shipment->tracking_number);

            return $newHistory;
        });

        return response()->json([
            'status'  => 'success',
            'message' => 'Riwayat pelacakan berhasil ditambahkan.',
            'data'    => $history,
        ], 201);
    }
}