<?php

namespace App\Http\Controllers;

use Validator;
use App;
use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionsController extends Controller
{
    public function getTransactions($account_id = null)
    {
        $validator = Validator::make(['id' => $account_id], [
            'id' => 'nullable|exists:accounts'
        ]);
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->with('global-warning', 'The account you selected does not exist');
        }

        if ($account_id) {
            $account = Account::with(['transactions' => function ($q) {
                return $q->orderBy('created_at', 'DESC');
            }])->find($account_id);
            $transactions = $account->transactions;
            return view('accounts.transactions.index', compact('account', 'transactions'));
        }

        $transactions = Transaction::with('account')->get();
        return view('accounts.transactions.index', compact('transactions'));
    }

    public function downloadTransaction($transaction_id)
    {
        $transaction = Transaction::with('account.client')->find($transaction_id);

        $pdf = App::make('snappy.pdf.wrapper');
        $pdf->loadView('accounts.transactions.download', compact('transaction'));

        return $pdf->setOption('no-outline', true)
            ->setOption('page-height', '8.89in')
            ->setOption('page-width', '5in')
            ->setOption('margin-left', '0')
            ->setOption('margin-right', '0')
            ->setOption('margin-top', '0')
            ->setOption('margin-bottom', '0')
            ->download('receipt-' . $transaction->receipt_no . '.pdf');
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:transactions',
            'account' => 'required|exists:accounts,id',
            'amount' => 'required|numeric',
            'particulars' => 'required'
        ]);

        if ($validator->fails()) {
            $error_list = '<ul><li>' . implode('</li><li>', $validator->errors()->all()) . '</li></ul>';
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($validator)
                ->with('global-warning', '<h3>Validation Error</h3>' . $error_list);
        }

        $transaction = Transaction::find($request->id);

        $transaction->account_id = $request->account;
        $transaction->particulars = $request->particulars;
        $transaction->type = $request->type;
        $transaction->method = $request->method;
        $transaction->amount = $request->amount;

        $transaction->save();

        return redirect()
            ->back()
            ->with('global-success', 'Transaction updated');
    }
}
