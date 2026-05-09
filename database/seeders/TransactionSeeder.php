<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Expense;
use App\Models\StockEntry;
use App\Models\StockEntryItem;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        $customers = Customer::inRandomOrder()->take(20)->get();
        $products  = Product::all()->keyBy('id');

        // ======================================================
        // 1. PENJUALAN (3 bulan terakhir, ~30 transaksi)
        // ======================================================
        $salesData = [
            // Bulan -3
            ['date' => Carbon::now()->subMonths(3)->setDay(8),  'customer_id' => 1, 'items' => [[1,12],[2,24],[7,6]]],
            ['date' => Carbon::now()->subMonths(3)->setDay(12), 'customer_id' => 2, 'items' => [[3,24],[4,12],[5,12]]],
            ['date' => Carbon::now()->subMonths(3)->setDay(15), 'customer_id' => 3, 'items' => [[10,6],[11,12]]],
            ['date' => Carbon::now()->subMonths(3)->setDay(20), 'customer_id' => 4, 'items' => [[14,2],[15,1]]],
            ['date' => Carbon::now()->subMonths(3)->setDay(25), 'customer_id' => 5, 'items' => [[17,8],[18,6]]],

            // Bulan -2
            ['date' => Carbon::now()->subMonths(2)->setDay(3),  'customer_id' => 6,  'items' => [[1,24],[2,12],[8,12]]],
            ['date' => Carbon::now()->subMonths(2)->setDay(7),  'customer_id' => 7,  'items' => [[4,24],[5,12],[6,12]]],
            ['date' => Carbon::now()->subMonths(2)->setDay(11), 'customer_id' => 8,  'items' => [[10,12],[12,6]]],
            ['date' => Carbon::now()->subMonths(2)->setDay(14), 'customer_id' => 9,  'items' => [[13,24],[11,12]]],
            ['date' => Carbon::now()->subMonths(2)->setDay(17), 'customer_id' => 10, 'items' => [[7,24],[8,24]]],
            ['date' => Carbon::now()->subMonths(2)->setDay(20), 'customer_id' => 11, 'items' => [[14,1],[16,2]]],
            ['date' => Carbon::now()->subMonths(2)->setDay(24), 'customer_id' => 12, 'items' => [[17,12],[18,8]]],
            ['date' => Carbon::now()->subMonths(2)->setDay(27), 'customer_id' => 13, 'items' => [[1,36],[3,24]]],

            // Bulan -1
            ['date' => Carbon::now()->subMonths(1)->setDay(2),  'customer_id' => 14, 'items' => [[2,24],[5,24],[9,12]]],
            ['date' => Carbon::now()->subMonths(1)->setDay(6),  'customer_id' => 15, 'items' => [[6,12],[7,12],[8,12]]],
            ['date' => Carbon::now()->subMonths(1)->setDay(9),  'customer_id' => 16, 'items' => [[10,6],[11,6],[12,6]]],
            ['date' => Carbon::now()->subMonths(1)->setDay(13), 'customer_id' => 17, 'items' => [[15,2],[16,2]]],
            ['date' => Carbon::now()->subMonths(1)->setDay(16), 'customer_id' => 18, 'items' => [[17,16],[18,12]]],
            ['date' => Carbon::now()->subMonths(1)->setDay(20), 'customer_id' => 1,  'items' => [[1,12],[4,12],[7,12]]],
            ['date' => Carbon::now()->subMonths(1)->setDay(23), 'customer_id' => 2,  'items' => [[13,12],[3,24]]],
            ['date' => Carbon::now()->subMonths(1)->setDay(28), 'customer_id' => 3,  'items' => [[14,1],[17,8]]],

            // Bulan ini
            ['date' => Carbon::now()->setDay(2),  'customer_id' => 4,  'items' => [[1,24],[2,12]]],
            ['date' => Carbon::now()->setDay(5),  'customer_id' => 5,  'items' => [[7,24],[8,12],[9,12]]],
            ['date' => Carbon::now()->setDay(8),  'customer_id' => 6,  'items' => [[10,12],[11,12]]],
            ['date' => Carbon::now()->setDay(10), 'customer_id' => 7,  'items' => [[12,6],[13,12]]],
            ['date' => Carbon::now()->setDay(12), 'customer_id' => 8,  'items' => [[15,2],[16,2]]],
            ['date' => Carbon::now()->setDay(14), 'customer_id' => 9,  'items' => [[17,8],[18,6]]],
            ['date' => Carbon::now()->setDay(15), 'customer_id' => 10, 'items' => [[4,24],[5,12],[6,12]]],
        ];

        $invoiceCounter = 1;
        foreach ($salesData as $index => $sd) {
            $subtotal    = 0;
            $saleItems   = [];
            $isLunas     = $index < 20; // 20 transaksi pertama Lunas
            $isPpn       = in_array($index % 5, [0, 2]); // sebagian pakai PPN

            foreach ($sd['items'] as [$productId, $qty]) {
                $product = $products[$productId];
                $price   = $product->price;
                $lineSubtotal = $qty * $price;
                $subtotal += $lineSubtotal;

                $saleItems[] = [
                    'product_id'      => $productId,
                    'description'     => $product->name,
                    'unit'            => $product->uom ?? 'PCS',
                    'quantity'        => $qty,
                    'price'           => $price,
                    'discount_percent'=> 0,
                    'discount_item'   => 0,
                    'subtotal'        => $lineSubtotal,
                ];

                // Kurangi stok
                $product->decrement('stock', min($qty, $product->stock));
            }

            $ppnAmount  = $isPpn ? round($subtotal * 0.11) : 0;
            $grandTotal = $subtotal + $ppnAmount;

            $invoiceNumber = 'INV/' . $sd['date']->format('Ymd') . '/' . str_pad($invoiceCounter, 3, '0', STR_PAD_LEFT);
            $invoiceCounter++;

            $sale = Sale::create([
                'customer_id'             => $sd['customer_id'],
                'invoice_type'            => 'INVOICE',
                'invoice_number'          => $invoiceNumber,
                'date'                    => $sd['date'],
                'due_date'                => $sd['date']->copy()->addDays(14),
                'subtotal'                => $subtotal,
                'discount_item_total'     => 0,
                'discount_invoice'        => 0,
                'discount_invoice_percent'=> 0,
                'is_ppn'                  => $isPpn,
                'ppn_amount'              => $ppnAmount,
                'grand_total'             => $grandTotal,
                'shipping_cost'           => 0,
                'status'                  => $isLunas ? 'Lunas' : 'Belum Lunas',
                'note'                    => '',
            ]);

            foreach ($saleItems as $item) {
                SaleItem::create(array_merge(['sale_id' => $sale->id], $item));
            }

            // Mutasi stok keluar
            $entry = StockEntry::create([
                'type' => 'KELUAR',
                'date' => $sd['date'],
                'note' => 'Dari penjualan ' . $invoiceNumber,
            ]);

            foreach ($saleItems as $item) {
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

        // ======================================================
        // 2. PENGELUARAN KAS (3 bulan terakhir)
        // ======================================================
        $expenseData = [
            // Bulan -3
            ['date' => Carbon::now()->subMonths(3)->setDay(1),  'category' => 'Gaji Karyawan',      'amount' => 8500000,  'note' => 'Gaji karyawan bulan ' . Carbon::now()->subMonths(3)->translatedFormat('F Y')],
            ['date' => Carbon::now()->subMonths(3)->setDay(5),  'category' => 'Listrik & Air',       'amount' => 850000,   'note' => 'Tagihan PLN dan PDAM'],
            ['date' => Carbon::now()->subMonths(3)->setDay(1),  'category' => 'Sewa Kantor',         'amount' => 3000000,  'note' => 'Sewa ruko bulan berjalan'],
            ['date' => Carbon::now()->subMonths(3)->setDay(10), 'category' => 'Operasional Gudang',  'amount' => 450000,   'note' => 'Bahan packing & karton'],
            ['date' => Carbon::now()->subMonths(3)->setDay(15), 'category' => 'Lain-lain',           'amount' => 250000,   'note' => 'Biaya transport pengiriman'],

            // Bulan -2
            ['date' => Carbon::now()->subMonths(2)->setDay(1),  'category' => 'Gaji Karyawan',      'amount' => 8500000,  'note' => 'Gaji karyawan bulan ' . Carbon::now()->subMonths(2)->translatedFormat('F Y')],
            ['date' => Carbon::now()->subMonths(2)->setDay(5),  'category' => 'Listrik & Air',       'amount' => 920000,   'note' => 'Tagihan PLN dan PDAM'],
            ['date' => Carbon::now()->subMonths(2)->setDay(1),  'category' => 'Sewa Kantor',         'amount' => 3000000,  'note' => 'Sewa ruko bulan berjalan'],
            ['date' => Carbon::now()->subMonths(2)->setDay(12), 'category' => 'Peralatan Kantor',    'amount' => 750000,   'note' => 'Tinta printer & ATK'],
            ['date' => Carbon::now()->subMonths(2)->setDay(18), 'category' => 'Operasional Gudang',  'amount' => 380000,   'note' => 'Pita selotip & wrap plastik'],
            ['date' => Carbon::now()->subMonths(2)->setDay(25), 'category' => 'Lain-lain',           'amount' => 300000,   'note' => 'Biaya bensin kendaraan operasional'],

            // Bulan -1
            ['date' => Carbon::now()->subMonths(1)->setDay(1),  'category' => 'Gaji Karyawan',      'amount' => 8500000,  'note' => 'Gaji karyawan bulan ' . Carbon::now()->subMonths(1)->translatedFormat('F Y')],
            ['date' => Carbon::now()->subMonths(1)->setDay(5),  'category' => 'Listrik & Air',       'amount' => 780000,   'note' => 'Tagihan PLN dan PDAM'],
            ['date' => Carbon::now()->subMonths(1)->setDay(1),  'category' => 'Sewa Kantor',         'amount' => 3000000,  'note' => 'Sewa ruko bulan berjalan'],
            ['date' => Carbon::now()->subMonths(1)->setDay(8),  'category' => 'Peralatan Kantor',    'amount' => 320000,   'note' => 'Pembelian map & alat tulis'],
            ['date' => Carbon::now()->subMonths(1)->setDay(20), 'category' => 'Operasional Gudang',  'amount' => 520000,   'note' => 'Box karton & bahan packing'],

            // Bulan ini
            ['date' => Carbon::now()->setDay(1),  'category' => 'Gaji Karyawan',     'amount' => 8500000, 'note' => 'Gaji karyawan bulan ' . Carbon::now()->translatedFormat('F Y')],
            ['date' => Carbon::now()->setDay(5),  'category' => 'Listrik & Air',      'amount' => 870000,  'note' => 'Tagihan PLN dan PDAM'],
            ['date' => Carbon::now()->setDay(1),  'category' => 'Sewa Kantor',        'amount' => 3000000, 'note' => 'Sewa ruko bulan berjalan'],
            ['date' => Carbon::now()->setDay(10), 'category' => 'Operasional Gudang', 'amount' => 410000,  'note' => 'Bahan packaging produk'],
        ];

        foreach ($expenseData as $ed) {
            Expense::create([
                'date'     => $ed['date'],
                'category' => $ed['category'],
                'amount'   => $ed['amount'],
                'note'     => $ed['note'],
            ]);
        }
    }
}
