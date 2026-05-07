<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Sale;
use App\Models\SaleItem;

class FixSaleDiscounts extends Command
{
    protected $signature = 'sales:fix-discounts {--dry-run : Preview changes without saving}';
    protected $description = 'Fix corrupted discount_item values in sale_items where discount_percent > 0 but discount_item is inconsistent';

    public function handle()
    {
        $isDryRun = $this->option('dry-run');
        $fixed = 0;

        $this->info($isDryRun ? '=== DRY RUN (no changes saved) ===' : '=== FIXING DATA ===');

        // Find items where discount_percent > 0 but discount_item doesn't match
        $items = SaleItem::where('discount_percent', '>', 0)->get();

        foreach ($items as $item) {
            $lineTotal        = $item->quantity * $item->price;
            $expectedDiscount = round($lineTotal * ($item->discount_percent / 100));
            $expectedSubtotal = round($lineTotal - $expectedDiscount);

            $discountDiffers  = abs($item->discount_item - $expectedDiscount) > 1;
            $subtotalDiffers  = abs($item->subtotal - $expectedSubtotal) > 1;

            if ($discountDiffers || $subtotalDiffers) {
                $sale = $item->sale;
                $this->line("Invoice: {$sale->invoice_number} | Item ID: {$item->id}");
                $this->line("  discount_percent : {$item->discount_percent}%");
                $this->line("  discount_item    : {$item->discount_item} → {$expectedDiscount}");
                $this->line("  subtotal         : {$item->subtotal} → {$expectedSubtotal}");

                if (!$isDryRun) {
                    $item->update([
                        'discount_item' => $expectedDiscount,
                        'subtotal'      => $expectedSubtotal,
                    ]);
                }
                $fixed++;
            }
        }

        // Recalculate sale-level totals for affected sales
        if (!$isDryRun && $fixed > 0) {
            $this->info("\nRecalculating sale totals...");

            $affectedSaleIds = SaleItem::where('discount_percent', '>', 0)
                ->pluck('sale_id')
                ->unique();

            foreach ($affectedSaleIds as $saleId) {
                $sale     = Sale::with('items')->find($saleId);
                $subtotal = $sale->items->sum('subtotal');
                $baseTotal = $subtotal - $sale->discount_invoice;
                $ppnPct   = (float) \App\Models\Setting::get('ppn_percentage', 11);
                $ppnAmt   = $sale->is_ppn ? round($baseTotal * ($ppnPct / 100), 2) : $sale->ppn_amount;
                $grandTotal = $baseTotal + $ppnAmt + $sale->shipping_cost;

                $sale->update([
                    'subtotal'    => $subtotal,
                    'ppn_amount'  => $ppnAmt,
                    'grand_total' => $grandTotal,
                ]);

                $this->line("  Recalculated sale #{$saleId} ({$sale->invoice_number}): subtotal={$subtotal}, grand_total={$grandTotal}");
            }
        }

        $this->info("\nDone. {$fixed} item(s) " . ($isDryRun ? 'need fixing.' : 'fixed.'));
        return 0;
    }
}
