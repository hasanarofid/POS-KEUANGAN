<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class StockReportPrintController extends Controller
{
    public function show(Request $request)
    {
        $query = Product::query()->where('is_track_stock', true);

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $products = $query->orderBy('name', 'asc')->get();
        $totalValue = $products->sum(fn($p) => $p->stock * $p->price);

        return view('reports.stock-print', [
            'products' => $products,
            'category' => $request->category,
            'totalValue' => $totalValue,
        ]);
    }
}
