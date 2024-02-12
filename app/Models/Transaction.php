<?php

namespace App\Models;

use NumberFormatter;
use Illuminate\Database\Eloquent\Model;
use Ogilo\BarcodeBundle\Utils\QrCode;
use Ogilo\BarcodeBundle\Utils\BarcodeGenerator;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $appends = ['amount_word', 'receipt_no', 'receipt_date', 'cr', 'dr', 'balance', 'transaction_date', 'barcode', 'qrcode'];

    function __construct()
    {
        parent::__construct();
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * Get the invoice that owns the Transaction
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function getAmountWordAttribute()
    {
        $f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
        return $f->format($this->amount);
    }

    public function getReceiptNoAttribute()
    {
        return str_pad($this->id, 4, '0', STR_PAD_LEFT);
    }
    public function getReceiptDateAttribute()
    {
        return $this->created_at->format('j-M-Y');
    }
    public function getCrAttribute()
    {
        return $this->type == 'CR' ? $this->amount : 0;
    }

    public function getDrAttribute()
    {
        return $this->type == 'DR' ? $this->amount : 0;
    }

    public function getBalanceAttribute()
    {
        return $this->cr - $this->dr;
    }

    public function getTransactionDateAttribute()
    {
        return $this->created_at;
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
