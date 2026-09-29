<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateTrackingStatusRequest;
use App\Models\Shipment;
use App\Models\TrackingHistory;
use App\Repositories\ShipmentTrackingRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class TrackingApiController extends Controller
{
    public function __construct(
        protected ShipmentTrackingRepository $trackingRepo
    ) {}

    public function track(string $trackingNumber): JsonResponse
    {
        $data = $this->trackingRepo->findByTrackingNumber(trim($trackingNumber));

        if (!$data) {
            return response()->json([
                'status'  => 'error',
                'message' => "Nomor resi [{$trackingNumber}] tidak ditemukan.",
            ], 404);
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Informasi pelacakan berhasil ditemukan.',
            'data'    => $data,
        ]);
    }

    public function updateStatus(UpdateTrackingStatusRequest $request): JsonResponse
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated) {
            $shipment = Shipment::where('tracking_number', $validated['tracking_number'])
                ->lockForUpdate()
                ->firstOrFail();

            TrackingHistory::create([
                'shipment_id' => $shipment->id,
                'branch_id'   => $validated['branch_id'] ?? null,
                'status'      => $validated['status'],
                'description' => $validated['description'],
                'recorded_at' => now(),
            ]);

            $shipment->update([
                'current_status' => $validated['status'],
            ]);
        });

        // Bersihkan cache Redis agar data terbaru langsung terbaca
        $this->trackingRepo->invalidateCache($validated['tracking_number']);

        return response()->json([
            'status'  => 'success',
            'message' => 'Status resi berhasil diperbarui.',
        ]);
    }
}