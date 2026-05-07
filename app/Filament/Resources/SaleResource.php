<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SaleResource\Pages;
use App\Filament\Resources\SaleResource\RelationManagers;
use App\Models\Sale;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Table;
use Filament\Support\RawJs;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;

class SaleResource extends Resource
{
    protected static ?string $model = Sale::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Penjualan';
    protected static ?string $navigationGroup = 'Penjualan';
    protected static ?int $navigationSort = 31;
    protected static ?string $slug = 'penjualan';
    protected static ?string $modelLabel = 'Penjualan';
    protected static ?string $pluralModelLabel = 'Penjualan';
    protected static ?string $recordTitleAttribute = 'invoice_number';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Penjualan')
                    ->schema([
                        Forms\Components\Select::make('customer_id')
                            ->label('Pelanggan')
                            ->relationship('customer', 'name')
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->name} - {$record->billing_city} ({$record->code})")
                            ->required()
                            ->searchable(['name', 'billing_city', 'code'])
                            ->live()
                            ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                $customer = \App\Models\Customer::find($state);
                                if ($customer) {
                                    $set('is_ppn', $customer->group === 'PPN');
                                }
                                self::calculateTotals($get, $set);
                            }),
                        Forms\Components\Select::make('invoice_type')
                            ->label('Jenis Invoice')
                            ->options([
                                'NORMAL' => 'Normal',
                                'SJM' => 'SJM',
                            ])
                            ->default('NORMAL')
                            ->live()
                            ->dehydrated(),

                        Forms\Components\TextInput::make('invoice_number')
                            ->label('Nomor Invoice')
                            ->default('INV/' . date('Ymd') . '/' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT))
                            ->required()
                            ->unique(ignoreRecord: true),
                        Forms\Components\DatePicker::make('date')
                            ->label('Tanggal')
                            ->required()
                            ->native(false),
                        Forms\Components\DatePicker::make('due_date')
                            ->label('Jatuh Tempo')
                            ->native(false),
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'Belum Lunas' => 'Belum Lunas',
                                'Lunas' => 'Lunas',
                                'Dibatalkan' => 'Dibatalkan',
                            ])
                            ->default('Belum Lunas')
                            ->required(),
                    ])->columns(2),

                Forms\Components\Section::make('Item')
                    ->schema([
                        Forms\Components\Repeater::make('items')
                            ->relationship()
                            ->schema([
                                Forms\Components\Select::make('product_id')
                                    ->label('Produk')
                                    ->relationship('product', 'name')
                                    ->allowHtml()
                                    ->getOptionLabelFromRecordUsing(function ($record) {
                                        $stock = number_format($record->stock, 0, ',', '.');
                                        $dus = $record->isi > 0 ? floor($record->stock / $record->isi) : 0;
                                        $stockInfo = "";
                                        
                                        return "
                                            <div>
                                                <div class='font-medium text-sm'>{$record->sku} - {$record->name}</div>
                                                <div class='text-xs opacity-70'>{$stockInfo}</div>
                                            </div>
                                        ";
                                    })
                                    ->searchable(['name', 'sku'])
                                    ->nullable()
                                    // ->createOptionForm([
                                    //     Forms\Components\TextInput::make('name')
                                    //         ->label('Nama Produk')
                                    //         ->required(),
                                    //     Forms\Components\TextInput::make('category')
                                    //         ->label('Kategori')
                                    //         ->default('Manual')
                                    //         ->datalist(fn () => \App\Models\Product::query()->whereNotNull('category')->distinct()->pluck('category')->toArray())
                                    //         ->required(),
                                    //     Forms\Components\Toggle::make('is_track_stock')
                                    //         ->label('Lacak Stok')
                                    //         ->default(false),
                                    //     Forms\Components\Select::make('uom')
                                    //         ->label('Satuan')
                                    //         ->options([
                                    //             'PCS' => 'PCS',
                                    //             'SET' => 'SET',
                                    //             'KG' => 'KG',
                                    //         ])
                                    //         ->default('PCS')
                                    //         ->required(),
                                    //     Forms\Components\TextInput::make('price')
                                    //         ->label('Harga Jual')
                                    //         ->prefix('Rp')
                                    //         ->numeric()
                                    //         ->required(),
                                    // ])

                                    ->live()
                                    ->columnSpan(4)
                                    ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                        $product = \App\Models\Product::find($state);
                                        if ($product) {
                                            $unit = $product->uom ?? 'PCS';
                                            $set('unit', $unit);
                                            
                                            
                                            $price = match($unit) {
                                                'DUS' => $product->price_per_carton,
                                                'SET' => ($product->uom === 'SET') ? $product->price : $product->price_per_set,
                                                'KG' => $product->price,
                                                default => $product->price,
                                            };
                                            $set('price', self::formatNumber($price));
                                            $set('quantity', 1);
                                        }
                                        self::updateItemSubtotal($get, $set);
                                    }),
                                Forms\Components\TextInput::make('description')
                                    ->label('Keterangan / Deskripsi')
                                    ->placeholder('Isi jika ada keterangan tambahan atau manual')
                                    ->dehydrated()
                                    ->nullable()
                                    ->columnSpan(8),

                                 Forms\Components\TextInput::make('unit')
                                    ->label('Satuan')
                                    ->datalist(['PCS', 'DUS', 'SET', 'KG'])
                                    ->required()
                                    ->live()
                                    ->columnSpan(1)
                                    ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                        $product = \App\Models\Product::find($get('product_id'));
                                        if ($product) {
                                            $price = match(strtoupper($state)) {
                                                'DUS' => $product->price_per_carton,
                                                'SET' => ($product->uom === 'SET') ? $product->price : $product->price_per_set,
                                                'KG' => $product->price,
                                                default => $product->price,
                                            };
                                            $set('price', self::formatNumber($price));
                                        }
                                        self::updateItemSubtotal($get, $set);
                                    }),
                                Forms\Components\TextInput::make('quantity')
                                    ->label('Jumlah')
                                    ->required()
                                    ->default(1)
                                    ->extraInputAttributes(['onkeypress' => 'return (event.charCode >= 48 && event.charCode <= 57) || event.charCode === 46 || event.charCode === 44'])
                                    ->live(debounce: 500)
                                    ->formatStateUsing(fn ($state) => self::formatNumber($state))
                                    ->dehydrateStateUsing(fn ($state) => self::parseNumber($state))
                                    ->afterStateUpdated(fn(Forms\Get $get, Forms\Set $set) => self::updateItemSubtotal($get, $set))
                                    ->columnSpan(1),
                                Forms\Components\TextInput::make('price')
                                    ->label('Harga')
                                    ->required()
                                    ->prefix('Rp')
                                    ->mask(RawJs::make("\$money(\$input, ',', '.', 2)"))
                                    ->live(debounce: 500)
                                    ->formatStateUsing(fn ($state) => self::formatNumber($state))
                                    ->dehydrateStateUsing(fn ($state) => self::parseNumber($state))
                                    ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set) {
                                        self::updateItemSubtotal($get, $set);
                                    })
                                    ->columnSpan(3),
                                Forms\Components\TextInput::make('discount_percent')
                                    ->label('Diskon %')
                                    ->numeric()
                                    ->default(0)
                                    ->live(debounce: 500)
                                    ->extraInputAttributes(['onkeypress' => 'return (event.charCode >= 48 && event.charCode <= 57) || event.charCode === 46 || event.charCode === 44'])
                                    ->formatStateUsing(fn ($state) => $state !== null ? rtrim(rtrim(number_format((float)$state, 2, '.', ''), '0'), '.') : '0')
                                    ->dehydrateStateUsing(fn ($state) => self::parseNumber($state))
                                    ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set, $state) {
                                        $price = self::parseNumber($get('price') ?? 0);
                                        $qty = self::parseNumber($get('quantity') ?? 1);
                                        $lineSubtotal = $price * $qty;
                                        
                                        $discountNominal = round($lineSubtotal * (self::parseNumber($state) / 100));
                                        $set('discount_item', self::formatMoney($discountNominal));
                                        self::updateItemSubtotal($get, $set);
                                    })
                                    ->columnSpan(1),
                                Forms\Components\TextInput::make('discount_item')
                                    ->label('Diskon Rp')
                                    ->default(0)
                                    ->prefix('Rp')
                                    ->mask(RawJs::make("\$money(\$input, ',', '.', 0)"))
                                    ->live(debounce: 500)
                                    ->formatStateUsing(fn ($state) => self::formatNumber($state, 0))
                                    ->dehydrateStateUsing(fn ($state) => self::parseNumber($state))
                                    ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set, $state) {
                                        $price = self::parseNumber($get('price') ?? 0);
                                        $qty = self::parseNumber($get('quantity') ?? 1);
                                        $lineSubtotal = $price * $qty;
                                        
                                        $nominal = self::parseNumber($state ?? 0);
                                        if ($lineSubtotal > 0) {
                                            $set('discount_percent', round(($nominal / $lineSubtotal) * 100, 2));
                                        }
                                        $set('discount_item', self::formatMoney($nominal));
                                        self::updateItemSubtotal($get, $set);
                                    })
                                    ->columnSpan(3),
                                Forms\Components\TextInput::make('subtotal')
                                    ->required()
                                    ->readOnly()
                                    ->prefix('Rp')
                                    ->formatStateUsing(fn ($state) => self::formatMoney($state))
                                    ->dehydrateStateUsing(fn ($state) => self::parseNumber($state))
                                    ->dehydrated()
                                    ->columnSpan(3),
                            ])
                            ->columns(12)
                            ->live()
                            ->afterStateUpdated(fn(Forms\Get $get, Forms\Set $set) => self::calculateTotals($get, $set))
                            ->extraAttributes([
                                'onkeydown' => "if (event.key === 'Enter') { event.preventDefault(); return false; }",
                            ]),
                        ]),

                Forms\Components\Section::make('Ringkasan')
                    ->schema([
                        Forms\Components\TextInput::make('subtotal')
                            ->readOnly()
                            ->formatStateUsing(fn ($state) => self::formatMoney($state))
                            ->dehydrateStateUsing(fn ($state) => self::parseNumber($state))
                            ->dehydrated(),
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('discount_invoice_percent')
                                    ->label('Diskon %')
                                    ->numeric()
                                    ->default(0)
                                    ->live(debounce: 500)
                                    ->extraInputAttributes(['onkeypress' => 'return (event.charCode >= 48 && event.charCode <= 57) || event.charCode === 46'])
                                    ->formatStateUsing(fn ($state) => $state !== null ? rtrim(rtrim(number_format((float)$state, 2, '.', ''), '0'), '.') : '0')
                                    ->dehydrateStateUsing(fn ($state) => self::parseNumber($state))
                                    ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set, $state) {
                                        $subtotal = self::parseNumber($get('subtotal') ?? 0);
                                        $discountNominal = round($subtotal * (self::parseNumber($state) / 100));
                                        $set('discount_invoice', self::formatMoney($discountNominal));
                                        self::calculateTotals($get, $set);
                                    }),
                                Forms\Components\TextInput::make('discount_invoice')
                                    ->label('Diskon Rp')
                                    ->default(0)
                                    ->prefix('Rp')
                                    ->mask(RawJs::make("\$money(\$input, ',', '.', 0)"))
                                    ->live(debounce: 500)
                                    ->formatStateUsing(fn ($state) => self::formatNumber($state, 0))
                                    ->dehydrateStateUsing(fn ($state) => self::parseNumber($state))
                                    ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set, $state) {
                                        $subtotal = self::parseNumber($get('subtotal') ?? 0);
                                        $nominal = self::parseNumber($state ?? 0);
                                        if ($subtotal > 0) {
                                            $set('discount_invoice_percent', round(($nominal / $subtotal) * 100, 2));
                                        }
                                        self::calculateTotals($get, $set);
                                    }),
                                Forms\Components\TextInput::make('shipping_cost')
                                    ->label('Ongkir')
                                    ->default(0)
                                    ->prefix('Rp')
                                    ->mask(RawJs::make("\$money(\$input, ',', '.', 0)"))
                                    ->live(debounce: 500)
                                    ->formatStateUsing(fn ($state) => self::formatNumber($state, 0))
                                    ->dehydrateStateUsing(fn ($state) => self::parseNumber($state))
                                    ->afterStateUpdated(fn (Forms\Get $get, Forms\Set $set) => self::calculateTotals($get, $set)),
                            ]),
                        Forms\Components\Checkbox::make('is_ppn')
                            ->label(fn () => 'Gunakan PPN (' . \App\Models\Setting::get('ppn_percentage', 11) . '%)')
                            ->live()
                            ->afterStateUpdated(function (Forms\Set $set, Forms\Get $get, $state) {
                                if (!$state) {
                                    $set('ppn_amount', 0);
                                }
                                self::calculateTotals($get, $set);
                            }),
                        Forms\Components\TextInput::make('ppn_amount')
                                    ->label(fn () => 'PPN (' . \App\Models\Setting::get('ppn_percentage', 11) . '%)')
                                    ->prefix('Rp')
                                    ->mask(RawJs::make("\$money(\$input, ',', '.', 2)"))
                                    ->live(debounce: 500)
                                    ->formatStateUsing(fn ($state) => self::formatNumber($state))
                                    ->dehydrateStateUsing(fn ($state) => self::parseNumber($state))
                                    ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set, $state) {
                                        if (self::parseNumber($state) > 0) {
                                            $set('is_ppn', true);
                                        }
                                        self::calculateTotals($get, $set, isPpnManual: true);
                                    }),
                                Forms\Components\TextInput::make('grand_total')
                                    ->readOnly()
                                    ->prefix('Rp')
                                    ->formatStateUsing(fn ($state) => self::formatMoney($state))
                                    ->dehydrateStateUsing(fn ($state) => self::parseNumber($state))
                                    ->dehydrated(),
                        Forms\Components\Textarea::make('note')
                            ->label('Catatan')
                            ->columnSpanFull(),
                        Forms\Components\Hidden::make('discount_item_total')
                            ->default(0),
                    ])->columns(2),
            ]);
    }

    public static function formatMoney($value): string
    {
        return self::formatNumber($value);
    }

    public static function formatNumber($value, $decimals = 2): string
    {
        if ($value === null || $value === '') return '';
        $parsed = self::parseNumber($value);

        // For 0 decimals: return directly without rtrim
        // (rtrim would incorrectly strip zeros from thousands separator, e.g. "51.000" → "51.")
        if ($decimals === 0) {
            return number_format($parsed, 0, ',', '.');
        }

        // For decimals > 0: only trim trailing zeros AFTER the decimal comma, not the whole string
        $formatted = number_format($parsed, $decimals, ',', '.');
        $parts = explode(',', $formatted);
        if (count($parts) === 2) {
            $decimal = rtrim($parts[1], '0');
            if ($decimal === '') {
                return $parts[0]; // e.g. "85.000" instead of "85.000,"
            }
            return $parts[0] . ',' . $decimal; // e.g. "8,5"
        }
        return $formatted;
    }

    public static function parseNumber($value): float
    {
        if (is_null($value) || $value === '') {
            return 0;
        }

        // If it's already a float/int
        if (is_float($value) || is_int($value)) {
            return (float)$value;
        }

        $value = (string)$value;
        $value = str_replace(['Rp', ' ', "\xc2\xa0"], '', $value); // Include non-breaking space

        if ($value === '') return 0;

        // Count dots and commas
        $dotCount = substr_count($value, '.');
        $commaCount = substr_count($value, ',');

        // Case 1: Standard Indonesian (1.234,56)
        if ($dotCount > 0 && $commaCount > 0) {
            $value = str_replace('.', '', $value);
            $value = str_replace(',', '.', $value);
            return (float)$value;
        }

        // Case 2: Only dots (1.234 or 1.234.567 or 8.5)
        if ($dotCount > 0 && $commaCount === 0) {
            if ($dotCount > 1) {
                // Multiple dots are always thousands
                return (float)str_replace('.', '', $value);
            }
            
            // Single dot: Ambiguous.
            // If there are exactly 3 digits after the dot, treat as thousands (standard ID).
            $parts = explode('.', $value);
            if (strlen($parts[1]) === 3) {
                return (float)str_replace('.', '', $value);
            }
            
            // Otherwise, treat as decimal (like 8.5)
            return (float)$value;
        }

        // Case 3: Only commas (1,234 or 8,5)
        if ($commaCount > 0 && $dotCount === 0) {
            if ($commaCount > 1) {
                // Multiple commas? Treat as thousands
                return (float)str_replace(',', '', $value);
            }
            
            // Single comma: In Indonesia this is always a decimal.
            return (float)str_replace(',', '.', $value);
        }

        // Case 4: No dots or commas
        return (float)$value;
    }

    public static function updateItemSubtotal(Forms\Get $get, Forms\Set $set): void
    {
        $quantity = self::parseNumber($get('quantity') ?? 0);
        $price = self::parseNumber($get('price') ?? 0);
        $discountLine = self::parseNumber($get('discount_item') ?? 0);

        $subtotal = round(($quantity * $price) - $discountLine);
        $set('subtotal', self::formatMoney($subtotal));
        
        // Explicitly trigger summary calculation at parent level
        self::calculateTotals($get, $set);
    }

    public static function calculateTotals(Forms\Get $get, Forms\Set $set, bool $isPpnManual = false): void
    {
        // Improved items detection for different scopes
        $itemsData = $get('items');
        $isInRow = false;
        
        if ($itemsData === null) {
            $itemsData = $get('../../items');
            if ($itemsData !== null) {
                $isInRow = true;
            }
        }
        
        $items = collect($itemsData ?? []);
        
        $subtotal = $items->sum(function ($item) {
            return self::parseNumber($item['subtotal'] ?? 0);
        });

        $discountItemTotal = $items->sum(function ($item) {
            return self::parseNumber($item['discount_item'] ?? 0);
        });
        
        $discountInvoice = self::parseNumber($get('discount_invoice') ?? $get('../../discount_invoice') ?? 0);
        $discountInvoicePercent = self::parseNumber($get('discount_invoice_percent') ?? $get('../../discount_invoice_percent') ?? 0);
        $shippingCost = self::parseNumber($get('shipping_cost') ?? $get('../../shipping_cost') ?? 0);
        $isPpn = $get('is_ppn') ?? $get('../../is_ppn') ?? false;

        $baseTotal = $subtotal - $discountInvoice;
        
        if ($isPpnManual) {
            $ppnAmount = self::parseNumber($get('ppn_amount') ?? $get('../../ppn_amount') ?? 0);
        } else {
            $ppnPercentage = (float) \App\Models\Setting::get('ppn_percentage', 11);
            $ppnAmount = $isPpn ? round($baseTotal * ($ppnPercentage / 100), 2) : 0;
        }

        $grandTotal = $baseTotal + $ppnAmount + $shippingCost;

        if ($isInRow) {
            $set('../../subtotal', self::formatMoney($subtotal));
            $set('../../discount_item_total', round($discountItemTotal));
            $set('../../discount_invoice', self::formatMoney($discountInvoice));
            $set('../../ppn_amount', self::formatMoney($ppnAmount));
            $set('../../grand_total', self::formatMoney($grandTotal));
        } else {
            $set('subtotal', self::formatMoney($subtotal));
            $set('discount_item_total', round($discountItemTotal));
            $set('discount_invoice', self::formatMoney($discountInvoice));
            $set('ppn_amount', self::formatMoney($ppnAmount));
            $set('grand_total', self::formatMoney($grandTotal));
        }
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('invoice_number')
                    ->label('Nomor #')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('date')
                    ->label('Tanggal')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Pelanggan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('note')
                    ->label('Keterangan')
                    ->searchable()
                    ->limit(30),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Lunas' => 'success',
                        'Belum Lunas' => 'danger',
                        'Dibatalkan' => 'gray',
                        default => 'gray',
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('age')
                    ->label('Umur (hr)')
                    ->state(function (Sale $record): int {
                        if (!$record->date)
                            return 0;
                        return now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($record->date)->startOfDay(), false) * -1;
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('grand_total')
                    ->label('Total')
                    ->formatStateUsing(fn ($state) => 'Rp ' . self::formatMoney($state))
                    ->sortable(),
                Tables\Columns\TextColumn::make('customer.category')
                    ->label('Kategori Pelanggan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('invoice_type')
                    ->label('Tipe')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'NORMAL' => 'info',
                        'SJM' => 'gray',
                        default => 'gray',
                    })
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('print')
                    ->label('Cetak Nota')
                    ->icon('heroicon-o-printer')
                    ->url(fn(Sale $record): string => route('sales.print', $record))
                    ->openUrlInNewTab(),
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make()
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->action(function (Collection $records) {
                            $records->each(function (Sale $record) {
                                try {
                                    $record->delete();
                                } catch (\Exception $e) {
                                    Notification::make()
                                        ->title("Gagal menghapus {$record->invoice_number}")
                                        ->body($e->getMessage())
                                        ->danger()
                                        ->persistent()
                                        ->send();
                                }
                            });

                            Notification::make()
                                ->title('Proses hapus selesai')
                                ->success()
                                ->send();
                        }),
                ]),
            ])
            ->defaultSort('date', 'desc');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['customer']);
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
            'index' => Pages\ListSales::route('/'),
            'create' => Pages\CreateSale::route('/create'),
            'edit' => Pages\EditSale::route('/{record}/edit'),
        ];
    }
}
