<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Client;
use App\Models\Quotation;
use Illuminate\Support\Str;
use App\Models\QuotationItem;
use App\Services\QuotationService;
use App\Http\Requests\StoreQuotationRequest;
use App\Http\Requests\UpdateQuotationRequest;

class QuotationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $search = request()->input('search');
        $quotations = Quotation::with('client', 'user', 'items')
            ->when($search, function ($query) use ($search) {
                $query->whereHas('client', function ($query) use ($search) {
                    $query->where('name', 'LIKE', '%' . $search . '%');
                });
            })
            ->orderBy('created_at', 'DESC')
            ->paginate(5)->through(fn(Quotation $quotation) => (object) [
                "id" => $quotation->id,
                "description" => $quotation->description,
                "validity" => $quotation->validity,
                "sub_total" => $quotation->subTotal(),
                "tax" => $quotation->tax(),
                "amount" => $quotation->amount(),
                "barcode" => $quotation->barcode,
                "qrcode" => $quotation->qrcode,
                "date" => $quotation->created_at->isoFormat("llll"),
                "client" => (object) [
                    "id" => $quotation->client->id,
                    "name" => $quotation->client->name,
                    "email" => $quotation->client->email,
                    "phone" => $quotation->client->phone,
                    "box_no" => $quotation->client->box_no,
                    "post_code" => $quotation->client->post_code,
                    "town" => $quotation->client->town,
                    "address" => $quotation->client->address,
                ],
                "user" => (object) [
                    "id" => $quotation->user->id,
                    "name" => $quotation->user->name,
                    "email" => $quotation->user->email,
                    "phone" => $quotation->user->phone,
                    "photo" => $quotation->user->profile_photo_url,
                ],
                "items" => $quotation->items->map(fn(QuotationItem $quotationItem) => [
                    "id" => $quotationItem->id,
                    "particulars" => $quotationItem->particulars,
                    "quantity" => $quotationItem->quantity,
                    "price" => $quotationItem->price,
                    "unit" => $quotationItem->unit,
                ]),
            ]);

        $clients = Client::orderBy("name", "ASC")->get()->map(fn(Client $client) => [
            "id" => $client->id,
            "name" => Str::upper($client->name),
        ]);

        return Inertia::render('Quotations/Index', ["quotations" => $quotations, "searchVal" => $search, "clients" => $clients]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreQuotationRequest $request, QuotationService $service)
    {
        $service->store(
            $request->client,
            $request->user()->id,
            $request->validity,
            $request->items,
            $request->description
        );

        return redirect()->back()->with("success", "Quotation created");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateQuotationRequest $request, Quotation $quotation, QuotationService $service)
    {
        $service->update(
            $quotation,
            $request->client,
            $request->user()->id,
            $request->validity,
            $request->items,
            $request->description
        );

        return redirect()->back()->with('success', 'Quotation updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Quotation $quotation)
    {
        return redirect()->back()->with('success', 'Quotation Deleted');
    }
}
