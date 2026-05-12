<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'landing');

Route::get('/sales/{sale}/print', [\App\Http\Controllers\SalePrintController::class, 'show'])->name('sales.print');
Route::get('/delivery-notes/{deliveryNote}/print', [\App\Http\Controllers\DeliveryNotePrintController::class, 'show'])->name('delivery-notes.print');
Route::get('/warehouse-pickups/{warehousePickup}/print', [\App\Http\Controllers\WarehousePickupPrintController::class, 'show'])->name('warehouse-pickups.print');
Route::get('/reports/sales/print', [\App\Http\Controllers\SalesReportPrintController::class, 'show'])->name('sales.report.print');
Route::get('/reports/sales/excel', [\App\Http\Controllers\SalesReportExcelController::class, 'show'])->name('sales.report.excel');

Route::get('/reports/purchase/print', [\App\Http\Controllers\PurchaseReportPrintController::class, 'show'])->name('purchase.report.print');
Route::get('/reports/stock/print', [\App\Http\Controllers\StockReportPrintController::class, 'show'])->name('stock.report.print');
Route::get('/reports/expense/print', [\App\Http\Controllers\ExpenseReportPrintController::class, 'show'])->name('expense.report.print');
