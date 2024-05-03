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

        if (config('billing.tax.show')) {
            return $this->subTotal() * ((int) config('billing.tax.vat.rate') / 100);
        }

        return 0;
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
            ->setLabel(null)
            ->setLabelFontSize(16)
            ->setImageType(QrCode::IMAGE_TYPE_PNG);

        return 'data:' . $qrCode->getContentType() . ';base64,' . $qrCode->generate();
    }

    function getTaxType()
    {
        if ($this->tax_type) {
            return $this->tax_type;
        }
        return config('billing.tax.vat.type');
    }

    function paid()
    {
        $amount = 0;
        $receipts = $this->transactions->where('type', 'CR');
        foreach ($receipts as $transaction) {
            $amount += $transaction->amount;
        }
        return $amount;
    }

    function balance()
    {
        return $this->amount() - $this->paid();
    }
}
