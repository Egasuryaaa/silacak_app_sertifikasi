<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Customer;
use App\Repositories\ShipmentTrackingRepository;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShipmentWebController extends Controller
{
    public function __construct(
        protected ShipmentTrackingRepository $trackingRepo
    ) {}

    /**
     * Halaman Publik: Lacak Resi Pengiriman (Mobile-friendly)
     */
    public function trackingPage(Request $request): View
    {
        $trackingNumber = trim((string) $request->query('resi', ''));
        $trackingData = null;
        $error = null;

        if (!empty($trackingNumber)) {
            $trackingData = $this->trackingRepo->findByTrackingNumber($trackingNumber);
            if (!$trackingData) {
                $error = "Nomor resi [{$trackingNumber}] tidak ditemukan.";
            }
        }

        return view('tracking', compact('trackingData', 'trackingNumber', 'error'));
    }

    /**
     * Halaman Admin Loket: Input Pengiriman & Hitung Ongkir
     */
    public function shipmentDeskPage(): View
    {
        $branches = Branch::orderBy('name')->get();
        $customers = Customer::orderBy('name')->get();

        return view('desk', compact('branches', 'customers'));
    }
}