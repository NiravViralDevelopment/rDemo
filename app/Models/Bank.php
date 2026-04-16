<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bank extends Model
{
    use HasFactory;

    protected $fillable = [
        'bank_name',
        'ifsc_code',
        'branch_name',
        'account_number',
        'account_holder_name',
        'opening_balance',
        'description',
    ];

    public function transactions(): HasMany
    {
        return $this->hasMany(BankTransaction::class);
    }

    public function credits(): HasMany
    {
        return $this->hasMany(BankTransaction::class)->where('type', 'credit');
    }

    public function debits(): HasMany
    {
        return $this->hasMany(BankTransaction::class)->where('type', 'debit');
    }
}
