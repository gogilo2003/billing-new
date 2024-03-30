<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Ogilo\BarcodeBundle\Utils\BarcodeGenerator;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Quotation extends Model
{
    use HasFactory;

    /**
     * Get all of the items for the Quotation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(QuotationItem::class);
    }
    /**
     * Get the user that owns the Quotation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the client that owns the Quotation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function getBarcodeAttribute()
    {
        $barcode = new BarcodeGenerator();
        $barcode->setText(str_pad($this->id, 6, '0', STR_PAD_LEFT));
        $barcode->setType(BarcodeGenerator::Code39);
        $barcode->setScale(2);
        $barcode->setThickness(10);
        $barcode->setFontSize(14);
        $barcode->setLabel("");
        $code = $barcode->generate();

        return 'data:image/png;base64,' . $code;
    }

    public function amount()
    {
        $amount = 0;
        $this->load('items');
        foreach ($this->items as $item) {
            $amount += $item->amount();
        }
        return $amount;
    }

    public function subTotal()
    {
        if (config('billing.tax.vat.type') === 'inclusive')
            return $this->amount() * (100 / (100 + (int) config('billing.tax.vat.rate')));

        return $this->amount();
    }

    public function tax()
    {
        if (config('billing.tax.vat.type') === 'inclusive')
            return $this->amount() * ((int) config('billing.tax.vat.rate') / (100 + (int) config('billing.tax.vat.rate')));

        return $this->amount() * ((int) config('billing.tax.vat.rate') / 100);
    }
}
