<?php

namespace App\Http\Controllers\Api;

use App\Models\Transaction;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\AccountResource;
use App\Models\Account;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
    public function index()
    {
        return AccountResource::collection(Account::with('client')->get());
    }

    public function getAccounts(Request $request)
    {
        $transactions = Transaction::all();

        $cr = 0;
        $dr = 0;
        $balance = 0;

        // foreach ($transactions as $transaction) {
        $cr += $transactions->sum('cr');
        $dr += $transactions->sum('dr');
        $balance += $transactions->sum('balance');
        // }

        $balance = $balance * -1;

        $total = $cr + $dr + $balance;

        $pcr = round($cr / $total * 100);
        $pdr = round($dr / $total * 100);
        $pbalance = round($balance / $total * 100);


        $lCr = $pcr . '% (' . number_format($cr, 2) . ')';
        $lDr = $pdr . '% (' . number_format($dr, 2) . ')';
        $lBalance = $pbalance . '% (' . number_format($balance, 2) . ')';

        $data = array(
            'labels' => [$pcr, $pdr, $pbalance],
            'series' => [$cr, $dr, $balance]
        );
        return response(json_encode($data))->header('Content-Type', 'application/json');
    }

    public function getMonths(Request $request)
    {
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        $start = new \DateTime(date('Y-M-j'));

        $start->sub(new \DateInterval('P11M'));

        $end = new \DateTime(date('Y-M-j'));
        $end->add(new \DateInterval('P1M'));

        $s = (int)$start->format('n');

        $crs = [];
        $drs = [];
        $bls = [];

        for ($i = $start; $i < $end; $i->add(new \DateInterval('P1M'))) {

            $labels[] = $i->format('Y-M');

            $ms = new \DateTime($i->format('Y-M-01'));
            $me = new \DateTime($i->format('Y-M-t'));

            $transactions = Transaction::where('created_at', '>=', $ms)->where('created_at', '<=', $me)->get();

            $crs[] = $transactions->sum('cr');
            $drs[] = $transactions->sum('dr');
            try {
                $bls[] = $transactions->count() && $transactions->sum('balance') ? (($transactions->sum('balance') < 0) ? ($transactions->sum('balance') * -1) : $$transactions->sum('balance')) : 0;
            } catch (\Exception $e) {
                $bls[] = 0; //$e->getMessage();
            }
        }

        $data = array(
            'labels' => $labels,
            'series' => [$crs, $drs, $bls],
        );

        return response(json_encode($data))->header('Content-Type', 'application/json');
    }

    function updateNotification(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:accounts'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $account = Account::find($request->id);
        $account->notification = $account->notification ? 0 : 1;
        $account->save();

        return response()->json([
            'success' => true,
            'notification' => $account->notification,
            "message" => 'Notification changed successfuly'
        ]);
    }

    function showNotification(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:accounts'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $account = Account::find($request->id);

        return response()->json([
            'success' => true,
            'notification' => $account->notification,
            "message" => 'Current nottification status'
        ]);
    }
}
