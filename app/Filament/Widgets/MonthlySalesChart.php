<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Sale;
use Illuminate\Support\Carbon;

class MonthlySalesChart extends ChartWidget
{
    protected static ?string $heading = 'Tren Penjualan Bulanan';
    protected static ?int $sort = 2;
    protected static string $color = 'warning';

    protected function getData(): array
    {
        $data = [];
        $labels = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $labels[] = $month->format('M Y');
            $data[] = Sale::where('status', 'Lunas')
                ->whereYear('date', $month->year)
                ->whereMonth('date', $month->month)
                ->sum('grand_total');
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Penjualan (Rp)',
                    'data' => $data,
                    'fill' => 'start',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
