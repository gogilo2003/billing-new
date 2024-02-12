<?php

namespace App\Http\Controllers\Api;

use App\Models\Client;
use App\Models\Account;
use App\Models\Invoice;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\InvoiceDetail;
use Ogilo\ApiResponseHelpers;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Http\Resources\InvoiceResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\AccountController;

class InvoiceController extends Controller
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
        $invoices = Invoice::with('account.client', 'items')
            ->whereIn('client_id', Client::where('name', 'LIKE', "%$search%")->pluck('id'))
            ->orderBy('created_at', 'DESC')
            ->paginate(5);
        return InvoiceResource::collection($invoices);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $account = Account::with('client')->find($request->account_id);
        $client_id = $account->client_id;

        $validator = Validator::make($request->all(), [
            'account_id' => 'required|integer|exists:accounts,id',
            'name' => ['required', Rule::unique('invoices', 'name')->where(function ($query) use ($client_id) {
                return $query->where('client_id', $client_id);
            })],
            'items' => 'required|min:1',
            'items.*.particulars' => 'required',
            'items.*.price' => 'required|numeric',
            'items.*.quantity' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator);
        }
        $invoice = new Invoice;

        $invoice->name = $request->name;
        $invoice->ref = $request->ref;
        $invoice->client_id = $account->client_id;
        $invoice->account_id = $account->id;

        $invoice->save();

        foreach ($request->items as $detail) {

            $item = new InvoiceDetail();

            $item->particulars = $detail['particulars'];
            $item->price = $detail['price'];
            $item->quantity = $detail['quantity'];

            $invoice->items()->save($item);
        }

        AccountController::transact($account, $invoice->name, 'DR', $invoice->amount(), null, $invoice->id);

        $invoice->load('items');

        return $this->storeSuccess('Invoice Stored', ['invoice' => new InvoiceResource($invoice)]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $validator = Validator::make(['id' => $id], ['id' => 'required|exists:invoices']);
        if ($validator->fails()) {
            return $this->validationError($validator);
        }

        return new InvoiceResource(Invoice::with('items', 'client')->find($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $account = Account::find($request->account_id);
        // $client_id = $account->client_id;
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:invoices,id',
            'account_id' => 'required|integer|exists:accounts,id',
            'name' => ['required', Rule::unique('invoices', 'name')->where(function ($query) use ($account) {
                return $query->where('account_id', $account->id);
            })->ignore($request->id)],
            'items' => 'required|min:1',
            'items.*.id' => 'nullable|integer|exists:invoice_details,id',
            'items.*.particulars' => 'required',
            'items.*.price' => 'required|numeric',
            'items.*.quantity' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator);
        }

        $invoice = Invoice::find($request->id);

        $invoice->name = $request->name;
        $invoice->ref = $request->ref;
        $invoice->client_id = $account->client_id;
        $invoice->account_id = $account->id;

        $invoice->save();

        foreach ($request->items as $detail) {
            $item = null;
            if (isset($detail['id'])) {
                $item = InvoiceDetail::find($detail['id']);
            } else {
                $item = new InvoiceDetail();
            }

            $item->particulars = $detail['particulars'];
            $item->price = $detail['price'];
            $item->quantity = $detail['quantity'];

            $invoice->items()->save($item);
        }

        $transaction = Transaction::where('invoice_id', $invoice->id)
            ->where('type', 'DR')
            ->first();

        $transaction->amount = $invoice->amount();

        $invoice->load('items');

        return $this->storeSuccess('Invoice Updated', ['invoice' => new InvoiceResource($invoice)]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $validator = Validator::make(['id' => $id], ['id' => 'required|exists:invoices']);

        if ($validator->fails()) {
            return $this->validationError($validator);
        }

        $invoice = Invoice::with('items', 'transactions');

        foreach ($invoice->items as $item) {
            $item->delete();
        }

        foreach ($invoice->transactions as $transaction) {
            $transaction->delete();
        }

        $invoice->delete();
    }
}
