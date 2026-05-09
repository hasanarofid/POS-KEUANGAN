<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseReportPrintController extends Controller
{
    public function show(Request $request)
    {
        $query = Expense::query();

        if ($request->filled('from')) {
            $query->whereDate('date', '>=', $request->from);
        }

        if ($request->filled('until')) {
            $query->whereDate('date', '<=', $request->until);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $expenses = $query->orderBy('date', 'desc')->get();
        $totalSum = $expenses->sum('amount');

        return view('reports.expense-print', [
            'expenses' => $expenses,
            'from' => $request->from,
            'until' => $request->until,
            'category' => $request->category,
            'totalSum' => $totalSum,
        ]);
    }
}
