<?php

namespace App\Models;

use Ogilo\BarcodeBundle\Utils\QrCode;
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
    public function getQrcodeAttribute()
    {
        $qrCode = new QrCode();
        $qrCode->setText(str_pad($this->id, 6, '0', STR_PAD_LEFT))
            ->setSize(300)
            ->setPadding(10)
            ->setErrorCorrection('high')
            ->setForegroundColor(array('r' => 0, 'g' => 0, 'b' => 0, 'a' => 0))
            ->setBackgroundColor(array('r' => 255, 'g' => 255, 'b' => 255, 'a' => 0))
            ->setLabel(str_pad($this->id, 6, '0', STR_PAD_LEFT))
            ->setLabelFontSize(16)
            ->setImageType(QrCode::IMAGE_TYPE_PNG);
        $code = $qrCode->generate();

        return 'data:image/png;base64,' . $code;
    }

    public function amount()
    {
        return $this->subTotal() + $this->tax();
    }

    public function subTotal()
    {
        $amount = 0;
        $this->load('items');
        foreach ($this->items as $item) {
            $amount += $item->amount();
        }

        return $amount;
    }

    public function tax()
    {
        $amount = 0;
        $this->load('items');
        foreach ($this->items as $item) {
            $amount += $item->amount();
        }

        return $amount * ((int) config('billing.tax.vat.rate') / 100);
    }

    function getTaxType()
    {
        if ($this->tax_type) {
            return $this->tax_type;
        }
        return config('billing.tax.vat.type');
    }
}
