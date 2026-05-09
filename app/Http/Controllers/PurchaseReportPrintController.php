<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PurchaseReportPrintController extends Controller
{
    public function show(Request $request)
    {
        $query = Purchase::query();

        if ($request->filled('from')) {
            $query->whereDate('date', '>=', $request->from);
        }

        if ($request->filled('until')) {
            $query->whereDate('date', '<=', $request->until);
        }

        $purchases = $query->orderBy('date', 'desc')->get();
        $totalSum = $purchases->sum('total');

        return view('reports.purchase-print', [
            'purchases' => $purchases,
            'from' => $request->from,
            'until' => $request->until,
            'totalSum' => $totalSum,
        ]);
    }
}
