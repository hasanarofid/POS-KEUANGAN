<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SalesOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalSales = \App\Models\Sale::where('status', 'Lunas')->sum('grand_total');
        $totalPurchases = \App\Models\Purchase::sum('total');
        $totalExpenses = \App\Models\Expense::sum('amount');
        $profit = $totalSales - $totalPurchases - $totalExpenses;

        return [
            Stat::make('Total Penjualan (Lunas)', 'Rp ' . number_format($totalSales, 0, ',', '.'))
                ->description('Total pendapatan bersih')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
            Stat::make('Total Pembelian', 'Rp ' . number_format($totalPurchases, 0, ',', '.'))
                ->description('Total belanja stok')
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->color('warning'),
            Stat::make('Total Pengeluaran Kas', 'Rp ' . number_format($totalExpenses, 0, ',', '.'))
                ->description('Total biaya operasional')
                ->descriptionIcon('heroicon-m-credit-card')
                ->color('danger'),
            Stat::make('Estimasi Laba Bersih', 'Rp ' . number_format($profit, 0, ',', '.'))
                ->description('Penjualan - Pembelian - Pengeluaran')
                ->descriptionIcon('heroicon-m-presentation-chart-line')
                ->color($profit >= 0 ? 'success' : 'danger'),
            
            Stat::make('Invoice Belum Lunas', \App\Models\Sale::where('status', 'Belum Lunas')->count())
                ->description('Piutang yang harus ditagih')
                ->descriptionIcon('heroicon-m-clock')
                ->color('danger'),
            Stat::make('Total Pelanggan', \App\Models\Customer::count())
                ->description('Total pelanggan terdaftar')
                ->descriptionIcon('heroicon-m-users')
                ->color('indigo'),
        ];
    }
}
