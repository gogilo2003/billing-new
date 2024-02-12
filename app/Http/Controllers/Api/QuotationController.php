<?php

namespace App\Http\Controllers\Api;

use App\Models\Client;
use App\Models\Quotation;
use Illuminate\Http\Request;
use Ogilo\ApiResponseHelpers;
use App\Services\QuotationService;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\QuotationResource;
use App\Http\Requests\V1\QuotationStoreRequest;
use App\Http\Requests\V1\QuotationUpdateRequest;

class QuotationController extends Controller
{
    use ApiResponseHelpers;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->has('search') ? $request->search : '';
        $quotations = Quotation::with('client', 'user', 'items')
            ->whereIn('client_id', Client::where('name', 'LIKE', "%$search%")->pluck('id'))
            ->orderBy('created_at', 'DESC')
            ->paginate(5);

        return QuotationResource::collection($quotations);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(QuotationStoreRequest $request, QuotationService $service)
    {
        $quotation = new QuotationResource($service->store(
            $request->client_id,
            $request->user()->id,
            $request->validity,
            $request->items,
            $request->description
        ));
        return $this->storeSuccess('Quotation stored', compact('quotation'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $quotation = Quotation::with('items', 'client', 'user')->findOrFail($id);
        $quotation->unsetRelation('user.accounts');
        return new QuotationResource($quotation);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(QuotationUpdateRequest $request, QuotationService $service)
    {
        $quotation = new QuotationResource($service->update(
            $request->id,
            $request->client_id,
            $request->user()->id,
            $request->validity,
            $request->items,
            $request->description
        ));
        return $this->updateSuccess("Quotation updated", compact('quotation'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
