<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasCompany;

class Expense extends Model
{
    use SoftDeletes, HasCompany;

    protected $fillable = [
        'company_id',
        'date',
        'category',
        'amount',
        'note',
        'attachment',
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
    ];
}
