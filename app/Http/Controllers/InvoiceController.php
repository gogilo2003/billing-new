<?php

namespace App\Http\Controllers;

use App;

use PDF;
use Validator;
use Inertia\Inertia;

use App\Models\Client;
use App\Models\Account;
use App\Models\Invoice;
use App\Models\Delivery;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\InvoiceDetail;
use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use App\Http\Requests\V1\PayInvoiceRequest;

class InvoiceController extends Controller
{
    public function index($client_id = null)
    {
        $invoices = null;
        $client = null;
        $search = request()->input('search');

        if ($client_id) {
            $client = Client::find($client_id);
        }
        $invoices = Invoice::with([
            'account.client',
            'transactions' => function ($query) {
                $query->where('type', 'CR');
            }
        ])
            ->orderBy('id', 'DESC')
            ->when($client_id, function ($query) use ($client_id) {
                $query->where('client_id', '=', $client_id);
            })
            ->when($search, function ($query) use ($search) {
                $query->whereHas('account.client', function ($query) use ($search) {
                    $query->where('name', 'LIKE', "%$search%");
                })->orWhere('name', 'LIKE', "%$search%");
            })
            ->paginate(10);

        $accounts = Account::with('client')
            ->get()->map(fn ($item) => [
                "id" => $item->id,
                // "name" => sprintf('%s - %s', $item->client->name, $item->name),
                "client" => $item->client->name,
                "name" => $item->name,
            ])
            ->sortBy(function ($item) {
                return [$item['client'], $item['name']];
            })->values()->all();

        // return view('invoices.index', compact('invoices', 'client'));
        return Inertia::render('Invoices/Index', [
            'invoices' => $invoices->through(function ($item) {
                return [
                    "id" => $item->id,
                    "name" => $item->name,
                    "ref" => $item->ref,
                    "amount" => $item->amount(),
                    "paid" => $item->paid(),
                    "balance" => $item->amount() - $item->paid(),
                    "barcode" => $item->barcode,
                    "qrcode" => $item->qrcode,
                    "date" => $item->created_at,
                    "account" => $item->account->id,
                    "client" => [
                        "id" => $item->account->client->id,
                        "name" => $item->account->client->name,
                        "phone" => $item->account->client->phone,
                        "email" => $item->account->client->email,
                        "postal_address" => ($item->account->client->box_no || $item->account->client->post_code || $item->account->client->town) ? sprintf(
                            "P.O. Box %s %s %s",
                            trim(
                                ltrim(
                                    Str::lower($item->account->client->box_no),
                                    'p.o. box'
                                )
                            ),
                            $item->account->client->post_code ? ' - ' . $item->account->client->post_code : '',
                            $item->account->client->town ? ', ' . $item->account->client->town : ''
                        ) : '',
                        "location" => $item->account->client->address,
                    ],
                    "items" => $item->items->map(fn ($item) => [
                        "id" => $item->id,
                        "particulars" => $item->particulars,
                        "quantity" => $item->quantity,
                        "price" => $item->price,
                    ]),
                    "receipts" => $item->transactions->map(fn ($item) => [
                        "id" => $item->id,
                        "particulars" => $item->particulars,
                        "method" => $item->method,
                        "amount" => $item->amount,
                        "transaction_ref" => $item->transaction_ref,
                        "date" => $item->created_at->isoFormat("ddd D MMM Y"),
                    ]),
                ];
            }),
            'client' => $client,
            'searchVal' => $search,
            'accounts' => $accounts,
            'receiptId' => session('receiptId'),
        ]);
    }

    public function create($client_id = null)
    {
        $client = null;

        if ($client_id) {
            $client = Client::find($client_id);
        }


        return view('invoices.new', compact('client'));
    }

    public function store(StoreInvoiceRequest $request)
    {

        $account = Account::find($request->input('account'));

        $invoice = new Invoice;

        $invoice->name = $request->input('name');
        $invoice->client_id = $account->client_id;
        $invoice->account_id = $account->id;
        $invoice->ref = $request->ref;
        $invoice->tax_type = $request->tax_type;

        $invoice->save();

        foreach ($request->input('items') as $item) {

            $invoiceItem = new InvoiceDetail();

            $invoiceItem->particulars = $item['particulars'];
            $invoiceItem->price = $item['price'];
            $invoiceItem->quantity = $item['quantity'];

            $invoice->items()->save($invoiceItem);
        }

        AccountController::transact($account, $invoice->name, 'DR', $invoice->amount(), null, $invoice->id);

        return redirect()
            ->back()
            ->with('success', 'Invoice Created');
    }
    public function update(UpdateInvoiceRequest $request, Invoice $invoice)
    {
        $account = Account::find($request->account);

        $invoice->name = $request->input('name');
        $invoice->client_id = $account->client_id;
        $invoice->account_id = $account->id;
        $invoice->ref = $request->ref;
        $invoice->tax_type = $request->tax_type;

        $invoice->save();

        foreach ($request->input('items') as $item) {

            $invoiceItem = isset($item["id"]) ? InvoiceDetail::find($item["id"]) : new InvoiceDetail();

            $invoiceItem->particulars = $item['particulars'];
            $invoiceItem->price = $item['price'];
            $invoiceItem->quantity = $item['quantity'];

            $invoice->items()->save($invoiceItem);
        }

        $transaction = Transaction::where('invoice_id', $invoice->id)->where('type', 'DR')->first();
        AccountController::transact($account, $invoice->name, 'DR', $invoice->amount(), null, $invoice->id ?? null, $transaction->id ?? null);

        return redirect()
            ->back()
            ->with('success', 'Invoice Updated');
    }

    public function show($id)
    {
        $invoice = Invoice::with('items')->find($id);
        return view('invoices.view', compact('invoice'));
    }

    public function downloadInvoice($id)
    {
        $invoice = Invoice::with('client')->find($id);
        $pdf = App::make('snappy.pdf.wrapper');
        $pdf->loadView('invoices.download', compact('invoice'))
            ->setOption('no-outline', true)
            ->setOption('margin-left', 0)
            ->setOption('margin-right', 0)
            ->setOption('margin-top', 48)
            ->setOption('margin-bottom', 13)
            ->setOption('header-html', public_path('pdf/header.html'))
            ->setOption('footer-html', public_path('pdf/footer.html'));
        return $pdf->download('Invoice#' . str_pad($invoice->id, 4, '0', 0) . '.pdf');
    }

    public function downloadDelivery($id)
    {
        $invoice = Invoice::with('client', 'delivery')->find($id);
        $pdf = App::make('snappy.pdf.wrapper');

        if ($invoice->delivery === null) {
            $delivery = new Delivery;
            $delivery->delivery_date = now();
            $invoice->delivery()->save($delivery);
        }
        $invoice->load('delivery');

        $client = $invoice->client;
        $delivery = (object)[
            "id" => $invoice->delivery->id,
            "delivery_date" => $invoice->delivery->delivery_date->isoFormat('D, d MMM, Y'),
            "items" => $invoice->items->map(fn (InvoiceDetail $item) => (object)[
                "id" => $item->id,
                "particulars" => $item->particulars,
                "quantity" => $item->quantity,
            ])
        ];

        $pdf->loadView(
            'invoices.delivery',
            [
                'client' => $client,
                'delivery' => $delivery,
                'invoice' => (object)[
                    "id" => $invoice->id,
                    "order_no" => $invoice->order_no,
                ]
            ]
        )
            ->setOption('no-outline', true)
            ->setOption('margin-left', 0)
            ->setOption('margin-right', 0)
            ->setOption('margin-top', 48)
            ->setOption('margin-bottom', 13)
            ->setOption('header-html', public_path('pdf/header.html'))
            ->setOption('footer-html', public_path('pdf/footer.html'));
        return $pdf->download('delivery#' . str_pad($invoice->delivery->id, 4, '0', 0) . '.pdf');
    }

    /**
     * Merge Invoices
     * @param \Illuminate\Http\Request $request
     * @return never
     */
    public function postMerge(Request $request)
    {
        dd($request->except('_token'));
    }

    function pay(PayInvoiceRequest $request, $id)
    {
        // Assuming you have a Receipt model with an ID
        $invoice = Invoice::find($id);

        $receipt = new Transaction();
        $receipt->invoice_id = $invoice->id;
        $receipt->amount = $request->amount;
        $receipt->particulars = $request->particulars;
        $receipt->method = $request->method;
        $receipt->transaction_ref = $request->transaction_ref;
        $receipt->type = "CR";
        $receipt->account_id = $invoice->account_id;
        $receipt->save();

        // Return the ID of the newly created receipt
        // return response()->json(['receiptId' => $receipt->id]);
        return redirect()->back()->with('success', 'Payment made successfully')->with('receiptId', $receipt->id);
    }

    function receipt($receipt_id)
    {
        $transaction = Transaction::with('account.client')->find($receipt_id);

        $pdf = App::make('snappy.pdf.wrapper');
        $pdf->loadView('accounts.transactions.download', compact('transaction'));

        return $pdf->setOption('no-outline', true)
            ->setOption('page-height', '8.89in')
            ->setOption('page-width', '4.5in')
            ->setOption('margin-left', '0')
            ->setOption('margin-right', '0')
            ->setOption('margin-top', '0')
            ->setOption('margin-bottom', '0')
            ->download('receipt-' . $transaction->receipt_no . '.pdf');
    }
}
