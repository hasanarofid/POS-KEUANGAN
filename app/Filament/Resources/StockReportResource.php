<?php

namespace App\Filament\Resources;

use App\Models\Product;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Enums\FiltersLayout;
use Illuminate\Database\Eloquent\Builder;

class StockReportResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = 'Laporan Stok';
    protected static ?string $navigationGroup = 'Laporan';
    protected static ?int $navigationSort = 3;
    protected static ?string $slug = 'laporan-stok';

    public static function canCreate(): bool { return false; }
    public static function canEdit($record): bool { return false; }
    public static function canDelete($record): bool { return false; }
    public static function canDeleteAny(): bool { return false; }

    public static function table(Table $table): Table
    {
        return $table
            ->query(Product::query()->where('is_track_stock', true))
            ->columns([
                TextColumn::make('sku')->label('SKU')->searchable()->sortable(),
                TextColumn::make('name')->label('Produk')->searchable()->sortable(),
                TextColumn::make('stock')
                    ->label('Stok (PCS)')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('isi_info')
                    ->label('Format (DUS + PCS)')
                    ->getStateUsing(function ($record) {
                        if ($record->isi > 0) {
                            $dus = floor($record->stock / $record->isi);
                            $pcs = $record->stock % $record->isi;
                            return "{$dus} DUS" . ($pcs > 0 ? " {$pcs} PCS" : "");
                        }
                        return "-";
                    }),
                TextColumn::make('price')
                    ->label('Harga/PCS')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('total_value')
                    ->label('Nilai Stok')
                    ->getStateUsing(fn ($record) => $record->stock * $record->price)
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.')),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->label('Kategori')
                    ->options(Product::whereNotNull('category')->distinct()->pluck('category', 'category')->toArray()),
            ], layout: FiltersLayout::AboveContent)
            ->filtersFormColumns(3)
            ->headerActions([
                Tables\Actions\Action::make('print')
                    ->label('Cetak Laporan')
                    ->icon('heroicon-o-printer')
                    ->color('info')
                    ->url(fn ($livewire) => route('stock.report.print', [
                        'category' => $livewire->tableFilters['category']['value'] ?? null,
                    ]))
                    ->openUrlInNewTab(),
                Tables\Actions\Action::make('export_excel')
                    ->label('Download Excel')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->action(fn() => null),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => StockReportResource\Pages\ManageStockReports::route('/'),
        ];
    }
}
