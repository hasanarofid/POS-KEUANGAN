<?php

namespace App\Filament\Resources;

use App\Models\Expense;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;

class ExpenseReportResource extends Resource
{
    protected static ?string $model = Expense::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';
    protected static ?string $navigationLabel = 'Laporan Pengeluaran';
    protected static ?string $navigationGroup = 'Laporan';
    protected static ?int $navigationSort = 4;
    protected static ?string $slug = 'rekap-pengeluaran';

    public static function canCreate(): bool { return false; }
    public static function canEdit($record): bool { return false; }
    public static function canDelete($record): bool { return false; }
    public static function canDeleteAny(): bool { return false; }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('date')
                    ->label('Tanggal')
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('category')
                    ->label('Kategori')
                    ->badge()->sortable(),
                TextColumn::make('amount')
                    ->label('Nominal')
                    ->money('IDR')
                    ->summarize(Tables\Columns\Summarizers\Sum::make()->label('Total Pengeluaran')->money('IDR')),
                TextColumn::make('note')
                    ->label('Keterangan')
                    ->limit(50),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->options([
                        'Listrik & Air' => 'Listrik & Air',
                        'Gaji Karyawan' => 'Gaji Karyawan',
                        'Sewa Kantor' => 'Sewa Kantor',
                        'Operasional Gudang' => 'Operasional Gudang',
                        'Peralatan Kantor' => 'Peralatan Kantor',
                        'Lain-lain' => 'Lain-lain',
                    ]),
                Filter::make('date')
                    ->form([
                        DatePicker::make('from')->label('Dari Tanggal'),
                        DatePicker::make('until')->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from'], fn (Builder $query, $date): Builder => $query->whereDate('date', '>=', $date))
                            ->when($data['until'], fn (Builder $query, $date): Builder => $query->whereDate('date', '<=', $date));
                    })
            ], layout: FiltersLayout::AboveContent)
            ->filtersFormColumns(3)
            ->headerActions([
                Tables\Actions\Action::make('print')
                    ->label('Cetak Laporan')
                    ->icon('heroicon-o-printer')
                    ->color('info')
                    ->url(fn ($livewire) => route('expense.report.print', [
                        'from' => $livewire->tableFilters['date']['from'] ?? null,
                        'until' => $livewire->tableFilters['date']['until'] ?? null,
                        'category' => $livewire->tableFilters['category']['value'] ?? null,
                    ]))
                    ->openUrlInNewTab(),
                Tables\Actions\Action::make('export_excel')
                    ->label('Download Excel')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->action(fn() => null),
            ])
            ->defaultSort('date', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ExpenseReportResource\Pages\ManageExpenseReports::route('/'),
        ];
    }
}
