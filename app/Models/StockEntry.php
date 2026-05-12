<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasCompany;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class StockEntry extends Model
{
    use LogsActivity, HasCompany;

    protected static function booted()
    {
        static::deleting(function ($stockEntry) {
            $stockEntry->items()->each(fn($item) => $item->delete());
        });
    }

    protected $fillable = [
        'company_id',
        'type',
        'date',
        'note',
    ];

    public function items()
    {
        return $this->hasMany(StockEntryItem::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->logOnly(['item_summary'])
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn(string $eventName) => "Mutasi Stok {$eventName} by " . (auth()->user()?->name ?? 'System'));
    }

    public function getItemSummaryAttribute(): string
    {
        return $this->items->map(function ($item) {
            return $item->product ? $item->product->name : 'Unknown Product';
        })->filter()->unique()->implode(', ');
    }
}
