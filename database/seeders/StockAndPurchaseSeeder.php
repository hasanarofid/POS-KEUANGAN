<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\StockEntry;
use App\Models\StockEntryItem;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class StockAndPurchaseSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::all();

        // ======================================================
        // 1. UPDATE HARGA PRODUK AGAR REALISTIS
        // ======================================================
        $prices = [
            1  => ['buy' => 85000,  'sell' => 105000],
            2  => ['buy' => 45000,  'sell' => 58000],
            3  => ['buy' => 28000,  'sell' => 35000],
            4  => ['buy' => 80000,  'sell' => 98000],
            5  => ['buy' => 42000,  'sell' => 55000],
            6  => ['buy' => 25000,  'sell' => 32000],
            7  => ['buy' => 90000,  'sell' => 115000],
            8  => ['buy' => 85000,  'sell' => 108000],
            9  => ['buy' => 48000,  'sell' => 60000],
            10 => ['buy' => 120000, 'sell' => 150000],
            11 => ['buy' => 65000,  'sell' => 82000],
            12 => ['buy' => 115000, 'sell' => 145000],
            13 => ['buy' => 60000,  'sell' => 75000],
            14 => ['buy' => 850000, 'sell' => 1050000],
            15 => ['buy' => 780000, 'sell' => 960000],
            16 => ['buy' => 700000, 'sell' => 860000],
            17 => ['buy' => 230000, 'sell' => 285000],
            18 => ['buy' => 185000, 'sell' => 230000],
        ];

        foreach ($products as $product) {
            if (isset($prices[$product->id])) {
                $product->update([
                    'price' => $prices[$product->id]['sell'],
                    'isi'   => in_array($product->id, [14,15,16]) ? 1 : (in_array($product->id, [17,18]) ? 4 : 12),
                ]);
            }
        }

        $products = Product::all()->keyBy('id');

        // ======================================================
        // 2. MUTASI STOK MASUK (Opname Awal - 3 bulan lalu)
        // ======================================================
        $initialEntry = StockEntry::create([
            'type' => 'MASUK',
            'date' => Carbon::now()->subMonths(3)->startOfMonth(),
            'note' => 'Stok awal / opname gudang',
        ]);

        $initialStock = [
            1 => 240, 2 => 180, 3 => 120, 4 => 200, 5 => 150,
            6 => 100, 7 => 160, 8 => 144, 9 => 96,
            10 => 72, 11 => 60, 12 => 48, 13 => 36,
            14 => 10, 15 => 8,  16 => 6,
            17 => 40, 18 => 32,
        ];

        foreach ($initialStock as $productId => $qty) {
            $product = $products[$productId];
            $isi = $product->isi ?: 1;
            $cartons = floor($qty / $isi);
            $units = $qty % $isi;

            StockEntryItem::create([
                'stock_entry_id'  => $initialEntry->id,
                'product_id'      => $productId,
                'quantity_carton' => $cartons,
                'quantity_unit'   => $units,
                'quantity'        => $qty,
            ]);
            $product->increment('stock', $qty);
        }

        // ======================================================
        // 3. PEMBELIAN (3 bulan terakhir)
        // ======================================================
        $suppliers = [
            'PT Sumber Kimia Nusantara',
            'CV Makmur Sejahtera',
            'UD Jaya Abadi',
            'PT Indo Chemical Supply',
        ];

        $purchaseData = [
            [
                'date'     => Carbon::now()->subMonths(2)->setDay(5),
                'supplier' => $suppliers[0],
                'items'    => [[1,120,85000],[2,96,45000],[7,80,90000]],
            ],
            [
                'date'     => Carbon::now()->subMonths(2)->setDay(15),
                'supplier' => $suppliers[1],
                'items'    => [[10,36,120000],[11,24,65000],[12,24,115000]],
            ],
            [
                'date'     => Carbon::now()->subMonths(1)->setDay(3),
                'supplier' => $suppliers[0],
                'items'    => [[3,60,28000],[4,100,80000],[5,72,42000]],
            ],
            [
                'date'     => Carbon::now()->subMonths(1)->setDay(18),
                'supplier' => $suppliers[2],
                'items'    => [[14,5,850000],[15,4,780000],[17,20,230000]],
            ],
            [
                'date'     => Carbon::now()->setDay(2),
                'supplier' => $suppliers[3],
                'items'    => [[6,48,25000],[8,60,85000],[9,48,48000]],
            ],
            [
                'date'     => Carbon::now()->setDay(10),
                'supplier' => $suppliers[0],
                'items'    => [[13,24,60000],[16,4,700000],[18,16,185000]],
            ],
        ];

        foreach ($purchaseData as $pd) {
            $totalPurchase = 0;
            $purchaseItems = [];

            foreach ($pd['items'] as [$productId, $qty, $cost]) {
                $subtotal = $qty * $cost;
                $totalPurchase += $subtotal;
                $purchaseItems[] = [
                    'product_id' => $productId,
                    'quantity'   => $qty,
                    'cost'       => $cost,
                    'subtotal'   => $subtotal,
                ];

                // Tambah stok dan buat mutasi
                $products[$productId]->increment('stock', $qty);
            }

            $purchase = Purchase::create([
                'supplier_name' => $pd['supplier'],
                'date'          => $pd['date'],
                'total'         => $totalPurchase,
                'note'          => 'Pembelian rutin',
            ]);

            foreach ($purchaseItems as $item) {
                PurchaseItem::create(array_merge(['purchase_id' => $purchase->id], $item));
            }

            // Catat mutasi stok masuk dari pembelian
            $entry = StockEntry::create([
                'type' => 'MASUK',
                'date' => $pd['date'],
                'note' => 'Dari pembelian - ' . $pd['supplier'],
            ]);

            foreach ($purchaseItems as $item) {
                $product = $products[$item['product_id']];
                $isi = $product->isi ?: 1;
                StockEntryItem::create([
                    'stock_entry_id'  => $entry->id,
                    'product_id'      => $item['product_id'],
                    'quantity_carton' => floor($item['quantity'] / $isi),
                    'quantity_unit'   => $item['quantity'] % $isi,
                    'quantity'        => $item['quantity'],
                ]);
            }
        }
    }
}
