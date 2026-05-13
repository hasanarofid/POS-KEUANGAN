<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\SaleItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class TopProductsChart extends ChartWidget
{
    protected static ?string $heading = '5 Produk Terlaris';
    protected static ?int $sort = 3;
    protected static string $color = 'success';

    protected function getData(): array
    {
        $topProducts = SaleItem::select('product_id', DB::raw('SUM(quantity) as total_qty'))
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        $data = [];
        $labels = [];

        foreach ($topProducts as $item) {
            $product = Product::find($item->product_id);
            if ($product) {
                $labels[] = $product->name;
                $data[] = (float) $item->total_qty;
            }
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Terjual',
                    'data' => $data,
                    'backgroundColor' => [
                        '#3b82f6', '#8b5cf6', '#10b981', '#f59e0b', '#f43f5e'
                    ],
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'indexAxis' => 'y',
            'scales' => [
                'x' => [
                    'display' => true,
                ],
            ],
        ];
    }
}
