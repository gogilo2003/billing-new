<?php

namespace App\Http\Controllers;

use App;

use PDF;
use Validator;
use Inertia\Inertia;

use App\Models\Client;
use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Requests\StoreAccountRequest;

class AccountController extends Controller
{
    public function index()
    {
        $accounts = [];
        $client = null;

        // dd($client_id);


        $search = request('search', null);
        // Retrieve the data from the database without sorting
        $accounts = Account::with(['client', 'transactions'])->when($search, function ($query) use ($search) {
            $query->where('name', 'LIKE', "%$search%")
                ->orWhereHas('client', function ($query) use ($search) {
                    $query->where('name', 'LIKE', "%$search%");
                });
        })->get()->map(fn ($item) => [
            "id" => $item->id,
            "name" => $item->name,
            "amount" => $item->dr,
            "paid" => $item->cr,
            "balance" => $item->balance,
            "latest_payment_date" => $item->latest_cr_date,
            "latest_charge_date" => $item->latest_dr_date,
            "latest_transaction_date" => $item->latest_transaction_date,
            "client" => [
                "id" => $item->client->id,
                "name" => Str::ucfirst(Str::lower($item->client->name)),
                "email" => Str::lower($item->client->email),
                "phone" => $item->client->phone,
                "box_no" => ltrim(Str::lower($item->client->box_no), 'p.o. box'),
                "post_code" => $item->client->post_code,
                "town" => $item->client->town,
                "address" => Str::title($item->client->address),
                "balance" => $item->client->balance,
                "latest_payment_date" => $item->client->latest_cr_date,
                "latest_charge_date" => $item->client->latest_dr_date,
                "latest_transaction_date" => $item->client->latest_transaction_date,
            ],
            "transactions" => $item->transactions->map(fn ($item) => [
                "id" => $item->id,
                "particulars" => $item->particulars,
                "type" => $item->type,
                "amount" => $item->amount,
                "amount_word" => $item->amount_word,
                "method" => $item->method,
                "cr" => $item->cr,
                "dr" => $item->dr,
                "transaction_date" => $item->transaction_date,
                "barcode" => $item->barcode,
                "qrcode" => $item->qrcode,
            ]),
        ])->toArray();

        // Sort the array by the "balance" accessor
        usort($accounts, function ($a, $b) {
            return $a['balance'] - $b['balance'];
        });

        // Paginate the sorted data
        $perPage = 10;

        $page = request('page', 1); // Get the current page from the request
        if ($page > ceil(count($accounts) / $perPage)) {
            $page = ceil(count($accounts) / $perPage);
        }
        $accountsPaginated = new \Illuminate\Pagination\LengthAwarePaginator(
            array_slice($accounts, ($page - 1) * $perPage, $perPage),
            count($accounts),
            $perPage,
            $page
        );

        $accountsPaginated->setPath(request()->url());

        // return view('clients.index', compact('clients'));
        $data = [
            'accounts' => $accountsPaginated,
            'clients' => Client::orderBy('name')->get()->map(fn ($item) => [
                "id" => $item->id,
                "name" => $item->name
            ])
        ];
        if ($search) {
            $data['searchVal'] = $search;
        }
        return Inertia::render('Accounts/Index', $data);
    }

    public function store(StoreAccountRequest $request)
    {

        $client = Client::find($request->input('client'));

        $account = new Account;
        $account->name = $request->input('name');

        $client->accounts()->save($account);

        return redirect()
            ->route('accounts', $request->input('client'))
            ->with('success', 'Account created successfully');
    }

    public function postTransaction(Request $request)
    {
        // dd($request->all());

        $validator = Validator::make($request->all(), [
            'type' => 'required',
            'account' => 'required|integer',
            'particulars' => 'required',
            'amount' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($validator)
                ->with('global-warning', 'Some fields failed validation. Please check and try again');
        }

        $account = Account::find($request->account);

        $this->transact($account, $request->particulars, $request->type, $request->amount, $request->method);

        return redirect()
            ->route('accounts-view', $account->id)
            ->with('global-success', 'Transaction posted successfully');
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'client' => 'required|integer',
            'name' => 'required|unique:accounts,name,' . $request->input('client') . ',client_id'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($validator)
                ->with('global-warning', 'Some fields failed validation. Please check and try again');
        }

        $account = Account::find($request->input('id'));

        $account->name = $request->input('name');
        $account->client_id = $request->input('client');

        $account->save();

        return redirect()
            ->route('accounts', $request->input('client'))
            ->with('global-success', 'Account updated successfully');
    }

    public static function transact($account, $particulars, $type, $amount, $method = null, $invoice_id = null, $transaction_id = null)
    {
        $transaction = null;

        if ($transaction_id) {
            $transaction = Transaction::find($transaction_id);
        } else {
            $transaction = new Transaction;
        }

        $transaction->particulars   = $particulars;
        $transaction->type          = $type;
        $transaction->amount        = $amount;
        $transaction->method        = $method;
        if ($invoice_id) {
            $transaction->invoice_id = $invoice_id;
        }

        $account->transactions()->save($transaction);
        return;
    }

    public function downloadAccount($id = null)
    {
        if ($id) {
            try {
                $account = Account::with(['client', 'transactions' => function ($query) {
                    return $query->orderBy("created_at", "DESC");
                }])->find($id);
                $pdf = App::make('snappy.pdf.wrapper');
                $pdf->loadView('accounts.download.account', compact('account'));
                return $pdf->setOption('no-outline', true)
                    ->setOption('margin-left', 0)
                    ->setOption('margin-right', 0)
                    ->setOption('margin-top', 48)
                    ->setOption('margin-bottom', 13)
                    ->setOption('header-html', public_path('pdf/header.html'))
                    ->setOption('footer-html', public_path('pdf/footer.html'))
                    ->download("Account#" . str_pad($account->id, 4, '0', 0) . '.pdf');
            } catch (\Exception $e) {
                return response($e);
            }
        } else {
            try {
                $accounts = Account::with(['client', 'transactions' => function ($query) {
                    return $query->orderBy("created_at", "DESC");
                }])->get();
                $pdf = App::make('snappy.pdf.wrapper');
                $pdf->loadView('accounts.download.all', compact('accounts'));
                return $pdf->setOption('no-outline', true)
                    ->setOption('margin-left', 0)
                    ->setOption('margin-right', 0)
                    ->setOption('margin-top', 48)
                    ->setOption('margin-bottom', 13)
                    ->setOption('header-html', public_path('pdf/header.html'))
                    ->setOption('footer-html', public_path('pdf/footer.html'))
                    ->download("Account#" . time() . '.pdf');
            } catch (\Exception $e) {
                return response($e);
            }
        }
    }
}
