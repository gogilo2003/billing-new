<?php

namespace App\Http\Controllers;

use App;

use PDF;
use Validator;
use App\Models\Client;

use App\Models\Account;
use App\Models\Invoice;
use App\Models\Delivery;
use App\Models\InvoiceDetail;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index($client_id = null)
    {
        $invoices = null;
        $client = null;

        if ($client_id) {
            $invoices = Invoice::with('account.client')->orderBy('id', 'DESC')->where('client_id', '=', $client_id)->get();
            $client = Client::find($client_id);
            // dd($client_id);
        } else {
            $invoices = Invoice::with('account.client')->orderBy('id', 'DESC')->get();
        }

        return view('invoices.index', compact('invoices', 'client'));
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
                ->with('global-warning', 'Some fields faild validation');
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
