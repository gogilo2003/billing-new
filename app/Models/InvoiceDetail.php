<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceDetail extends Model
{
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function amount()
    {
        return $this->quantity * $this->price;
    }

    function getPriceAttribute($value)
    {
        if (config("billing.tax.show")) {
            if ($this->invoice->getTaxType() === 'inclusive') {
                return $value * (100 / (100 + (int) config('billing.tax.vat.rate')));
            }
        }

        return $value;
    }
}
