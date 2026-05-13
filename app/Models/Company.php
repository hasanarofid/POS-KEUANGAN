<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Filament\Models\Contracts\HasAvatar;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Company extends Model implements HasAvatar
{
    public function getFilamentAvatarUrl(): ?string
    {
        return asset('images/favicon-default.png');
    }
    protected $fillable = [
        'name',
        'slug',
        'address',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
