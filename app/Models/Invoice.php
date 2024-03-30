<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Ogilo\BarcodeBundle\Utils\QrCode;
use Ogilo\BarcodeBundle\Utils\BarcodeGenerator;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    protected $appends = ['barcode', 'qrcode'];

    public function items()
    {
        return $this->hasMany(InvoiceDetail::class, 'invoice_id');
    }

    /**
     * Get all of the transactions for the Invoice
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function subTotal()
    {
        if (config('billing.tax.vat.type') === 'inclusive')
            return $this->amount() * (100 / (100 + (int)config('billing.tax.vat.rate')));

        return $this->amount();
    }

    public function tax()
    {
        if (config('billing.tax.vat.type') === 'inclusive')
            return $this->amount() * ((int)config('billing.tax.vat.rate') / (100 + (int)config('billing.tax.vat.rate')));

        return $this->amount() * ((int)config('billing.tax.vat.rate') / 100);
    }

    public function amount()
    {
        $amount = 0;
        foreach ($this->items as $item) {
            $amount += $item->amount();
        }

        return $amount;
    }

    public function grandTotal()
    {
        if (config('billing.tax.vat.type') === 'inclusive')
            return $this->amount();

        return $this->amount() + $this->tax();
    }

    public function delivery()
    {
        return $this->hasOne(Delivery::class);
    }

    /**
     * Get the account that owns the Invoice
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

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
    public function getQRcodeAttribute()
    {
        $qrCode = new QrCode();
        $qrCode
            ->setText($this->id)
            ->setSize(300)
            ->setPadding(10)
            ->setErrorCorrection('high')
            ->setForegroundColor(array('r' => 0, 'g' => 0, 'b' => 0, 'a' => 0))
            ->setBackgroundColor(array('r' => 255, 'g' => 255, 'b' => 255, 'a' => 0))
            ->setLabel('Scan Qr Code')
            ->setLabelFontSize(16)
            ->setImageType(QrCode::IMAGE_TYPE_PNG);

        return  'data:' . $qrCode->getContentType() . ';base64,' . $qrCode->generate();
    }
}
