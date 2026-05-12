<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DefaultCompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $company = \App\Models\Company::firstOrCreate(
            ['slug' => 'default'],
            [
                'name' => 'ArusKas',
                'address' => 'Semarang, Indonesia',
            ]
        );

        // Assign all users to this company
        \App\Models\User::all()->each(function ($user) use ($company) {
            $user->companies()->syncWithoutDetaching([$company->id]);
        });

        // Assign all data to this company
        $models = [
            \App\Models\Customer::class,
            \App\Models\DeliveryNote::class,
            \App\Models\Expense::class,
            \App\Models\Product::class,
            \App\Models\ProductionReturn::class,
            \App\Models\Purchase::class,
            \App\Models\Sale::class,
            \App\Models\Setting::class,
            \App\Models\StockEntry::class,
            \App\Models\WarehousePickup::class,
            \App\Models\WarehouseReturn::class,
        ];

        foreach ($models as $model) {
            $model::whereNull('company_id')->update(['company_id' => $company->id]);
        }
    }
}
