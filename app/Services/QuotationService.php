<?php

namespace App\Services;

use App\Models\Quotation;
use App\Models\QuotationItem;
use Illuminate\Support\Facades\App;

class QuotationService
{
    /**
     * Store a new Quotation
     *
     * @param int $client_id
     * @param int $user_id
     * @param int $validity
     * @param array $items
     * @param String $description
     *
     * @return \App\Models\Quotation
     */
    function store(int $client_id, int $user_id, int $validity, array $items, string $description = "",$notes=""): Quotation
    {
        $quotation = new Quotation();
        $quotation->client_id = $client_id;
        $quotation->user_id = $user_id;
        $quotation->validity = $validity;
        $quotation->description = $description;
        $quotation->notes = $notes;
        $quotation->save();

        foreach ($items as $item) {
            $quote = new QuotationItem();
            $quote->quotation_id = $quotation->id;
            $quote->particulars = $item['particulars'];
            $quote->quantity = $item['quantity'];
            $quote->price = $item['price'];
            $quote->unit = isset($item['unit']) ? $item['unit'] : null;
            $quote->save();
        }

        $quotation->load('items', 'user', 'client');

        return $quotation;
    }

    /**
     * Update existing Quotation
     *
     * @param int $id
     * @param int $client_id
     * @param int $user_id
     * @param int $validity
     * @param array $items
     * @param String $description
     *
     * @return \App\Models\Quotation
     */
    function update(Quotation $quotation, int $client_id, int $user_id, int $validity, array $items, string $description = "",$notes="")
    {
        // $quotation = Quotation::find($id);
        $quotation->client_id = $client_id;
        $quotation->user_id = $user_id;
        $quotation->validity = $validity;
        $quotation->description = $description;
        $quotation->notes = $notes;
        $quotation->save();

        foreach ($items as $item) {
            $quote = isset($item['id']) ? QuotationItem::find($item['id']) : new QuotationItem();
            $quote->quotation_id = $quotation->id;
            $quote->particulars = $item['particulars'];
            $quote->quantity = $item['quantity'];
            $quote->price = $item['price'];
            $quote->unit = isset($item['unit']) ? $item['unit'] : null;
            $quote->save();
        }

        $quotation->load('items', 'user', 'client');

        return $quotation;
    }

    /**
     * Prepare quotation for download
     *
     * @param int $id
     *
     * return PDF
     */
    public function download(int $id)
    {
        $quotation = Quotation::with('client')->find($id);
        $pdf = App::make('snappy.pdf.wrapper');
        $pdf->loadView('quotations.download', compact('quotation'))
            ->setOption('no-outline', true)
            ->setOption('margin-left', 0)
            ->setOption('margin-right', 0)
            ->setOption('margin-top', 48)
            ->setOption('margin-bottom', 13)
            ->setOption('header-html', public_path('pdf/header.html'))
            ->setOption('footer-html', public_path('pdf/footer.html'));

        return $pdf;
    }
}
