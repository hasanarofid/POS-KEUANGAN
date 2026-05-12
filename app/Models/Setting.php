<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasCompany;
use Filament\Facades\Filament;

class Setting extends Model
{
    use HasCompany;
    protected $fillable = [
        'company_id',
        'key',
        'value',
        'display_name',
        'type',
        'group',
    ];

    public static function get($key, $default = null)
    {
        $tenantId = Filament::getTenant()?->id;
        $setting = self::where('key', $key)
            ->where('company_id', $tenantId)
            ->first();
        return $setting ? $setting->value : $default;
    }

    public static function set($key, $value)
    {
        $tenantId = Filament::getTenant()?->id;
        return self::updateOrCreate(
            [
                'key' => $key,
                'company_id' => $tenantId,
            ],
            [
                'value' => $value,
                'display_name' => str($key)->replace('_', ' ')->title(),
            ]
        );
    }
}
