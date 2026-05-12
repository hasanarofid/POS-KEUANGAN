<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasCompany;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Support\Facades\Auth;

class WarehouseReturn extends Model
{
    use SoftDeletes, LogsActivity, HasCompany;

    protected $fillable = [
        'company_id',
        'return_number',
        'date',
        'note',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function items()
    {
        return $this->hasMany(WarehouseReturnItem::class);
    }

    protected static function booted()
    {
        static::deleting(function ($warehouseReturn) {
            $warehouseReturn->items()->each(fn($item) => $item->delete());
        });
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn(string $eventName) => "Retur Gudang {$eventName} by " . (Auth::user()?->name ?? 'System'));
    }
}
