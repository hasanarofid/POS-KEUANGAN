<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExpenseResource\Pages;
use App\Filament\Resources\ExpenseResource\RelationManagers;
use App\Models\Expense;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ExpenseResource extends Resource
{
    protected static ?string $model = Expense::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationLabel = 'Pengeluaran Kas';
    protected static ?string $modelLabel = 'Pengeluaran';
    protected static ?string $pluralModelLabel = 'Pengeluaran Kas';
    protected static ?string $navigationGroup = 'Transaksi';
    protected static ?int $navigationSort = 100;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Pengeluaran')
                    ->schema([
                        Forms\Components\DatePicker::make('date')
                            ->label('Tanggal')
                            ->default(now())
                            ->required()
                            ->native(false),
                        Forms\Components\TextInput::make('category')
                            ->label('Kategori')
                            ->placeholder('Contoh: Listrik, Gaji, Sewa, dll')
                            ->datalist([
                                'Listrik & Air',
                                'Gaji Karyawan',
                                'Sewa Kantor',
                                'Operasional Gudang',
                                'Peralatan Kantor',
                                'Lain-lain',
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('amount')
                            ->label('Nominal')
                            ->required()
                            ->prefix('Rp')
                            ->numeric()
                            ->mask(\Filament\Support\RawJs::make("\$money(\$input, ',', '.', 0)"))
                            ->dehydrateStateUsing(fn ($state) => (float) str_replace(['.', ','], ['', '.'], $state)),
                        Forms\Components\Textarea::make('note')
                            ->label('Keterangan')
                            ->columnSpanFull(),
                        Forms\Components\FileUpload::make('attachment')
                            ->label('Bukti Pembayaran / Nota')
                            ->directory('expenses')
                            ->image()
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->label('Tanggal')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('category')
                    ->label('Kategori')
                    ->searchable()
                    ->badge(),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Nominal')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('note')
                    ->label('Keterangan')
                    ->limit(50)
                    ->searchable(),
                Tables\Columns\ImageColumn::make('attachment')
                    ->label('Bukti'),
            ])
            ->defaultSort('date', 'desc')
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('category')
                    ->options([
                        'Listrik & Air' => 'Listrik & Air',
                        'Gaji Karyawan' => 'Gaji Karyawan',
                        'Sewa Kantor' => 'Sewa Kantor',
                        'Operasional Gudang' => 'Operasional Gudang',
                        'Peralatan Kantor' => 'Peralatan Kantor',
                        'Lain-lain' => 'Lain-lain',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListExpenses::route('/'),
            'create' => Pages\CreateExpense::route('/create'),
            'view' => Pages\ViewExpense::route('/{record}'),
            'edit' => Pages\EditExpense::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
