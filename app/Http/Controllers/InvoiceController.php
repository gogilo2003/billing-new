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
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\InvoiceDetail;

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
        $invoices = Invoice::with('account.client')
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

        $accounts = Account::with(['client' => function ($query) {
            $query->orderBy('name');
        }])
            ->get()->map(fn ($item) => [
                "id" => $item->id,
                "name" => sprintf('%s - %s', $item->client->name, $item->name),
            ]);

        // return view('invoices.index', compact('invoices', 'client'));
        return Inertia::render('Invoices/Index', [
            'invoices' => $invoices->through(function ($item) {
                return [
                    "id" => $item->id,
                    "name" => $item->name,
                    "ref" => $item->ref,
                    "amount" => $item->amount(),
                    "barcode" => $item->barcode,
                    "qrcode" => $item->qrcode,
                    "client" => [
                        "id" => $item->account->client->id,
                        "name" => $item->account->client->name,
                        "phone" => $item->account->client->phone,
                        "email" => $item->account->client->email,
                        "postal_address" => ($item->account->client->box_no || $item->account->client->post_code || $item->account->client->town) ? sprintf(
                            "P.O. Box %s %s %s",
                            trim(ltrim(
                                Str::lower($item->account->client->box_no),
                                'p.o. box'
                            )),
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
                        "total" => $item->price * $item->quantity,
                    ]),
                ];
            }),
            'client' => $client,
            'searchVal' => $search,
            'accounts' => $accounts,
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

    public function store(Request $request)
    {
        // dd($request->all());

        $validator = Validator::make($request->all(), [
            'account'    => 'required|integer',
            'name'        => 'required',
            'details'    => 'required|min:1'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($validator)
                ->with('global-warning', 'Some fields failed validation');
        }

        $account = Account::find($request->input('account'));

        $invoice = new Invoice;

        $invoice->name         = $request->input('name');
        $invoice->client_id = $account->client_id;
        $invoice->account_id = $account->id;

        $invoice->save();

        foreach ($request->input('details') as $detail) {

            $item = new InvoiceDetail();

            $item->particulars     = $detail['particulars'];
            $item->price         = $detail['price'];
            $item->quantity     = $detail['quantity'];

            $invoice->items()->save($item);
        }

        AccountController::transact($account, $invoice->name, 'DR', $invoice->amount());

        return redirect()
            ->route('invoices-view', $invoice->id)
            ->with('global-success', 'Invoice Created');
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
            $invoice->delivery()->save($delivery);
        }
        $invoice->load('delivery');

        $pdf->loadView('invoices.delivery', compact('invoice'))
            ->setOption('no-outline', true)
            ->setOption('margin-left', 0)
            ->setOption('margin-right', 0)
            ->setOption('margin-top', 48)
            ->setOption('margin-bottom', 13)
            ->setOption('header-html', public_path('pdf/header.html'))
            ->setOption('footer-html', public_path('pdf/footer.html'));
        return $pdf->download('delivery#' . str_pad($invoice->delivery->id, 4, '0', 0) . '.pdf');
    }

    public function postMerge(Request $request)
    {
        dd($request->except('_token'));
    }
}
