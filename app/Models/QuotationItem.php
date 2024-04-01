<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QuotationItem extends Model
{
    use HasFactory;

    /**
     * Get the quotation that owns the QuotationItem
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function quotation(): BelongsTo
    {
        return $this->belongsTo(Quotation::class);
    }

    public function amount()
    {
        return $this->quantity * $this->price;
    }

    function getPriceAttribute($value)
    {

        if ($this->quotation->getTaxType() === 'inclusive') {
            return $value * (100 / (100 + (int) config('billing.tax.vat.rate')));
        }

        return $value;
    }
}
