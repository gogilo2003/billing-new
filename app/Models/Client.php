<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Client extends Model
{
    protected $appends = ['balance', 'latest_cr_date', 'latest_dr_date', 'latest_transaction_date'];

    public function accounts()
    {
        return $this->hasMany(Account::class);
    }

    /**
     * Get all of the invoices for the Client
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function invoices(): HasManyThrough
    {
        return $this->hasManyThrough(Invoice::class, Account::class);
    }

    public function cr()
    {
        $cr = 0;
        foreach ($this->accounts as $account) {
            $cr += $account->cr();
        }
        return $cr;
    }

    public function dr()
    {
        $dr = 0;
        foreach ($this->accounts as $account) {
            $dr += $account->dr();
        }
        return $dr;
    }

    public function balance($formated = true)
    {
        $balance = 0;
        foreach ($this->accounts as $account) {
            $balance += $account->balance();
        }
        return $formated ? number_format($balance, 2) : $balance;
    }

    public function getBalanceAttribute()
    {
        return $this->balance(false);
    }

    public function getPhoneAttribute($value)
    {
        return clean_isdn($value);
    }

    public function getLatestCrDateAttribute()
    {
        $accounts = $this->accounts
            ->sortByDateDesc('latest_cr_date')
            ->pluck('latest_cr_date');

        return $accounts[0] ?? null;
    }

    public function getLatestDrDateAttribute()
    {
        $accounts = $this->accounts
            ->sortByDateDesc('latest_dr_date')
            ->pluck('latest_dr_date');

        return $accounts[0] ?? null;
    }

    public function getLatestTransactionDateAttribute()
    {
        $accounts = $this->accounts
            ->sortByDateDesc('latest_transaction_date')
            ->pluck('latest_transaction_date');

        return $accounts[0] ?? null;
    }

    /**
     * Get all of the domains for the Client
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function domains(): HasMany
    {
        return $this->hasMany(Domain::class);
    }

    /**
     * Get all of the transactions for the Client
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function transactions(): HasManyThrough
    {
        return $this->hasManyThrough(Transaction::class, Account::class);
    }

    /**
     * Get all of the quotations for the Client
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function quotations(): HasMany
    {
        return $this->hasMany(Quotation::class);
    }
}
